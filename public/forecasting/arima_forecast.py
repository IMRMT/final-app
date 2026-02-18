import json
import sys
import os
import pandas as pd
from statsmodels.tsa.arima.model import ARIMA
from pmdarima import auto_arima

# Get paths from command-line arguments
sales_file = sys.argv[1]
forecast_file = sys.argv[2]

# Load JSON sales data
with open(sales_file, "r") as f:
    raw_data = json.load(f)

if not raw_data:
    print("No sales data provided.")
    sys.exit(1)

type = raw_data[0].get("type", "sma")  # default to SMA
df = pd.DataFrame(raw_data)

df['period'] = pd.to_datetime(df['period'])
df.set_index('period', inplace=True)

# Set frequency and fill missing values
if type == 'arima':
    df = df.asfreq('MS').last("50M")
    forecast_periods = 1
    forecast_range = pd.date_range(start=df.index[-1] + pd.DateOffset(months=1), periods=forecast_periods, freq='MS')
    df = df.fillna(0)

    if len(df) < 3:
        print("Error: Not enough data points to build ARIMA model.")
        sys.exit(1)

    try:
        from statsmodels.tsa.stattools import adfuller
        result = adfuller(df['total_quantity'])
        if (result[1] > 0.05):
            stepwise_model = auto_arima(
            df['total_quantity'],
            start_p=0, start_q=0, max_p=2, max_q=2,
            d=1,         # ini untuk data nonstationary
            seasonal=False, m=1,
            trace=True, stepwise=True,
            suppress_warnings=True
            )
        else:
            stepwise_model = auto_arima(
            df['total_quantity'],
            start_p=0, start_q=0, max_p=2, max_q=2,   # ini untuk stasionary
            seasonal=False, m=1,
            trace=True, stepwise=True,
            suppress_warnings=True
            )
        order = stepwise_model.order
        model = ARIMA(df['total_quantity'], order=order)
        model_fit = model.fit()
    except Exception as e:
        print("Error fitting ARIMA model:", str(e))
        sys.exit(1)

    forecast = model_fit.forecast(steps=forecast_periods)
    forecast_dict = {str(date.date()): float(qty) for date, qty in zip(forecast_range, forecast)}

    with open(forecast_file, "w") as f:
        json.dump(forecast_dict, f)

    actual_values = df['total_quantity'].tail(forecast_periods).values
    forecast_values = forecast[:len(actual_values)]

    if all(actual_values):
        mape = 100 * (abs(actual_values - forecast_values) / actual_values).mean()
        print(f"MAPE: {mape:.2f}%")
    else:
        print("MAPE: Cannot calculate due to zero actual values.")

else:
    df = df.asfreq('MS').last("12M")
    forecast_periods = 1
    forecast_range = pd.date_range(start=df.index[-1] + pd.DateOffset(months=1), periods=forecast_periods, freq='MS')
    df = df.fillna(0)

    if len(df) < 3:
        print("Error: Not enough data points for SMA forecast.")
        sys.exit(1)

    # --- SIMPLE MOVING AVERAGE FORECAST (rolling 3-month) ---
    sma_series = df['total_quantity'].copy()
    sma_forecast = []

    for _ in range(forecast_periods):
        avg = sma_series[-3:].mean()
        sma_forecast.append(avg)
        sma_series = pd.concat([sma_series, pd.Series([avg], index=[forecast_range[len(sma_forecast)-1]])])

    # Save forecast to JSON
    forecast_dict = {str(date.date()): float(qty) for date, qty in zip(forecast_range, sma_forecast)}

    with open(forecast_file, "w") as f:
        json.dump(forecast_dict, f)

    # Optional MAPE calculation
    actual_values = df['total_quantity'].tail(forecast_periods).values
    forecast_values = sma_forecast[:len(actual_values)]

    if all(actual_values):
        mape = 100 * (abs(actual_values - forecast_values) / actual_values).mean()
        print(f"MAPE: {mape:.2f}%")
    else:
        print("MAPE: Cannot calculate due to zero actual values.")