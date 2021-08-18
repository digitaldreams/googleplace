# PHP SDK for Google Places APIs 
Location aware web application uses google-places-api([Place Search](http://github.com), [Place Details](https://developers.google.com/places/web-service/details), [Geocoding](https://developers.google.com/maps/documentation/geocoding/start) , [Distance Matrix](https://developers.google.com/maps/documentation/distance-matrix/start), [Timezone](https://developers.google.com/maps/documentation/timezone/intro), [Elevation](https://developers.google.com/maps/documentation/elevation/start)
) a lot. They are not linked to each other also its hard to find a easy and time saving php libraray to work with these api. These libraray will need less knowldege about google api and give you flavour of OOP.
### Setting
Add this line on your composer.json
```javascript
"require":{
    "digitaldream/googleplace":"1.*"
}
```
You need to set your google api keys on top of your page. Like below
```php
   \GooglePlace\Request::$api_key = 'YOUR_GOOGLE_PLACES_API_KEY';
 ```
 
### Nearby Search
A Nearby Search lets you search for places within a specified area. For example you can search all the Restaurents of your city.
```php
   $rankBy = new \GooglePlace\Services\Nearby([
            'location' => '23.823168,90.367728',
            'rankby' => 'distance',
            'type' => 'bank'
        ]
    );
    $rankBy->places(); // it will return \Collection each contains a object of GooglePlace\Services\Place
    /* Google Return 60 places divide by 20 each request.
     To get next 20 result you have to call nextPage method.
     */
     print_r($rankBy->nextPage()); // it will return \GooglePlace\Response
```
### Text Search
Text Search Service is a web service that returns information about a set of places based on a string — for example "pizza in New York" 

```php
  $textSearch = new \GooglePlace\Services\TextSearch([
  'query' => 'Restaurants in Mirpur'
  ]);
   $places = $textSearch->places(); //same as nearby
```
### Place Details
A Place Details request returns more comprehensive information about the indicated place such as its complete address, phone number, user rating and reviews. You need to pass place_id or a reference from a Place Search

```php
$place=new \GooglePlace\Services\Place([
 'placeid'=>'any place id'
]);
$place->get();
echo $place->address();
echo $place->phone();
print_r($place->photos()); // returns Collection each contains a GooglePlace\Helpers\PlacePhoto object
print_r($place->reviews()) // return Collection
print_r($place->timezone(true)); // return  Timezone API response
print_r($place->distance($place2)) // return Distance Matrix API response
print_r($place->elevation()) // return Elevation API response
```
### Geocoding
You can get places by a place name or latitude and longitude.
```php
 $geocoding = new \GooglePlace\Services\Geocoding([
        'address' => 'House 13,Road 10,Section 11,Mirpur,Dhaka'
    ]);
    print_r($geocoding->places());
    
  $reverseGeocoding=   new \GooglePlace\Services\Geocoding([
        'address' =>  'latlng' => '23.8163589,90.3709893'
    ]);
       print_r($reverseGeocoding->places()); //same as nearby
```
### Distance Matrix API
Distance Matrix API is a service that provides travel distance and time for a matrix of origins and destinations, based on the recommended route between start and end points

```php
 $distanceMatrix = new \GooglePlace\Services\DistanceMatrix([
        'origins' => ['Dhaka University, Dhaka'],
        'destinations' => ['National University of Bangldesh, Gazipur']]);
        
    print_r($distanceMatrix->calculate());
```
### Timezone API
Time Zone API provides time offset data for locations on the surface of the earth. The API returns the name of that time zone, the time offset from UTC, and the daylight savings offset.
```php
 $timezone= new \GooglePlace\Services\Timezone([
    'location'=>'23.8163589,90.3709893',
    timestamp=time()
 ])
 $response=$timezone->get();
 echo $response->timeZoneId // return Asia/Dhaka
```

### Elevation API
Get the altitude(height from sea level in meters) of a given place. 
```php
$elevation =new \GooglePlace\Services\Elevation([
    'locations'=>'23.8163589,90.3709893'
]);
print_r($elevation->get()); 
```
