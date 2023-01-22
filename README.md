## Running

- Start docker services with `sail up -d`
- Make sure you fill API keys in `.env`
- Run `sail artisan wg:init-metrics` to create needed metrics in WG API
- Listen to queue jobs `sail artisan queue:listen`
- Run `sail artisan weather:process ...locations`
- Tail debug logs to see what's happening `tail -f storage/logs/laravel.log`

## How this works

This works in an event-based chain, which fetches coordinates for a given location, then gets weather data at those coordinates, and finally
pushes weather data to WG.

## Steps

Each step runs as a standalone queue job for retry-ability and possibility to increase work capacity by adding more queue workers.

1. `weather:process ...locations` command dispatches an individual `GeolocateCoordinates` job for each location, after fetching
coordinates through `GeocodingService`, dispatches `CoordinatesFetched` event.
2. After receiving `CoordinatesFetched` event, listener `FetchWeatherByCoordinates` fetches weather data of given coordinates through `WeatherResolver`, dispatches `WeatherFetched` event.
3. After receiving `WeatherFetched` event, listener `PushWeatherToWhatagraph` pushes weather data as `temperature`, `feels_like`, and `pressure` metrics to WG using `Whatagraph` client.

## Services

### `GeocodingService`
This contract has two implementations:
- `CachingGeocoder`: decorates another instance of `GeocodingService`. It looks for coordinates of given location in cache and either returns the result if found or passes the task to the decorated service. If decorated service returns a result, the `CachingGeocoder` stores it in cache.
- `OpenWeatherMapGeocoder`: uses `GeocodingClient` to fetch coordinates of given location from `OpenWeatherMap API`.

### `WeatherResolver`
This contract has two implementations:
- `CachingWeatherResolver`: decorates another instance of `WeatherResolver`. It looks for weather data of given coordinates in cache and either returns the result if found or passes the task to decorated service. If decorated service returns a result, the `CachingWeatherResolver` stores it in cache.
- `OpenWeatherMapResolver`: uses `WeatherClient` to fetch weather data at given coordinates from `OpenWeatherMap API`.
