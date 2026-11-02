<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountryUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $updates = [
            ['id' => 1, 'latitude' => 31.791702, 'longitude' => -7.092620], // Afghanistan
            ['id' => 2, 'latitude' => 41.153332, 'longitude' => 20.168331], // Albania
            ['id' => 3, 'latitude' => 28.033886, 'longitude' => 1.659626], // Algeria
            ['id' => 4, 'latitude' => 42.546245, 'longitude' => 1.601554], // Andorra
            ['id' => 5, 'latitude' => -11.202692, 'longitude' => 17.873887], // Angola
            ['id' => 6, 'latitude' => 17.060816, 'longitude' => -61.796428], // Antigua and Barbuda
            ['id' => 7, 'latitude' => -38.416097, 'longitude' => -63.616672], // Argentina
            ['id' => 8, 'latitude' => 40.069099, 'longitude' => 45.038189], // Armenia
            ['id' => 9, 'latitude' => -25.274398, 'longitude' => 133.775136], // Australia
            ['id' => 10, 'latitude' => 47.516231, 'longitude' => 14.550072], // Austria
            ['id' => 11, 'latitude' => 40.143105, 'longitude' => 47.576927], // Azerbaijan
            ['id' => 12, 'latitude' => 25.034280, 'longitude' => -77.396280], // Bahamas
            ['id' => 13, 'latitude' => 26.066700, 'longitude' => 50.557700], // Bahrain
            ['id' => 14, 'latitude' => 23.684994, 'longitude' => 90.356331], // Bangladesh
            ['id' => 15, 'latitude' => 13.193887, 'longitude' => -59.543198], // Barbados
            ['id' => 16, 'latitude' => 53.709807, 'longitude' => 27.953389], // Belarus
            ['id' => 17, 'latitude' => 50.503887, 'longitude' => 4.469936], // Belgium
            ['id' => 18, 'latitude' => 17.189877, 'longitude' => -88.497650], // Belize
            ['id' => 19, 'latitude' => 9.307690, 'longitude' => 2.315834], // Benin
            ['id' => 20, 'latitude' => 32.290275, 'longitude' => -64.757370], // Bermuda
            ['id' => 21, 'latitude' => 27.514162, 'longitude' => 90.433601], // Bhutan
            ['id' => 22, 'latitude' => -16.290154, 'longitude' => -63.588653], // Bolivia
            ['id' => 23, 'latitude' => 43.915886, 'longitude' => 17.679076], // Bosnia and Herzegovina
            ['id' => 24, 'latitude' => -22.328474, 'longitude' => 24.684866], // Botswana
            ['id' => 25, 'latitude' => -14.235004, 'longitude' => -51.925280], // Brazil
            ['id' => 26, 'latitude' => 18.420695, 'longitude' => -64.639968], // British Indian Ocean Territory
            ['id' => 27, 'latitude' => 4.535277, 'longitude' => 114.727669], // Brunei
            ['id' => 28, 'latitude' => 42.733883, 'longitude' => 25.485830], // Bulgaria
            ['id' => 29, 'latitude' => 12.238333, 'longitude' => -1.561593], // Burkina Faso
            ['id' => 30, 'latitude' => -3.373056, 'longitude' => 29.918886], // Burundi
            ['id' => 31, 'latitude' => 12.565679, 'longitude' => 104.990963], // Cambodia
            ['id' => 32, 'latitude' => 7.369722, 'longitude' => 12.354722], // Cameroon
            ['id' => 33, 'latitude' => 56.130366, 'longitude' => -106.346771], // Canada
            ['id' => 34, 'latitude' => 16.002082, 'longitude' => -24.013197], // Cape Verde
            ['id' => 35, 'latitude' => 19.313300, 'longitude' => -81.254600], // Cayman Islands
            ['id' => 36, 'latitude' => 6.611111, 'longitude' => 20.939444], // Central African Republic
            ['id' => 37, 'latitude' => 15.454166, 'longitude' => 18.732207], // Chad
            ['id' => 38, 'latitude' => -35.675147, 'longitude' => -71.542969], // Chile
            ['id' => 39, 'latitude' => 35.861660, 'longitude' => 104.195397], // China
            ['id' => 40, 'latitude' => -10.447525, 'longitude' => 105.690449], // Christmas Island
            ['id' => 41, 'latitude' => -12.164165, 'longitude' => 96.870956], // Cocos (Keeling) Islands
            ['id' => 42, 'latitude' => 4.570868, 'longitude' => -74.297333], // Colombia
            ['id' => 43, 'latitude' => -11.875001, 'longitude' => 43.872219], // Comoros
            ['id' => 44, 'latitude' => -4.038333, 'longitude' => 21.758664], // Congo (Kinshasa)
            ['id' => 45, 'latitude' => -0.228021, 'longitude' => 15.827659], // Congo (Brazzaville)
            ['id' => 46, 'latitude' => -21.236736, 'longitude' => -159.777671], // Cook Islands
            ['id' => 47, 'latitude' => 9.748917, 'longitude' => -83.753428], // Costa Rica
            ['id' => 48, 'latitude' => 45.943161, 'longitude' => 24.966760], // Croatia
            ['id' => 49, 'latitude' => 21.521757, 'longitude' => -77.781167], // Cuba
            ['id' => 50, 'latitude' => 12.169570, 'longitude' => -68.990020], // Curaçao
            ['id' => 51, 'latitude' => 35.126413, 'longitude' => 33.429859], // Cyprus
            ['id' => 52, 'latitude' => 49.817492, 'longitude' => 15.472962], // Czech Republic
            ['id' => 53, 'latitude' => 7.539989, 'longitude' => -5.547080], // Côte d'Ivoire
            ['id' => 54, 'latitude' => 56.263920, 'longitude' => 9.501785], // Denmark
            ['id' => 55, 'latitude' => 11.803749, 'longitude' => 42.590275], // Djibouti
            ['id' => 56, 'latitude' => 15.414999, 'longitude' => -61.370976], // Dominica
            ['id' => 57, 'latitude' => 18.735693, 'longitude' => -70.162651], // Dominican Republic
            ['id' => 58, 'latitude' => -1.831239, 'longitude' => -78.183406], // Ecuador
            ['id' => 59, 'latitude' => 26.820553, 'longitude' => 30.802498], // Egypt
            ['id' => 60, 'latitude' => 13.794185, 'longitude' => -88.896530], // El Salvador
            ['id' => 61, 'latitude' => 1.650801, 'longitude' => 10.267895], // Equatorial Guinea
            ['id' => 62, 'latitude' => 15.179384, 'longitude' => 39.782334], // Eritrea
            ['id' => 63, 'latitude' => 58.595272, 'longitude' => 25.013607], // Estonia
            ['id' => 64, 'latitude' => 8.460555, 'longitude' => 38.713764], // Ethiopia
            ['id' => 65, 'latitude' => -51.796253, 'longitude' => -59.523613], // Falkland Islands
            ['id' => 66, 'latitude' => 61.892635, 'longitude' => -6.911806], // Faroe Islands
            ['id' => 67, 'latitude' => -16.578193, 'longitude' => 179.414413], // Fiji
            ['id' => 68, 'latitude' => 64.963051, 'longitude' => 26.066693], // Finland
            ['id' => 69, 'latitude' => 46.227638, 'longitude' => 2.213749], // France
            ['id' => 70, 'latitude' => 3.933889, 'longitude' => -53.125782], // French Guiana
            ['id' => 71, 'latitude' => -17.679742, 'longitude' => -149.406843], // French Polynesia
            ['id' => 72, 'latitude' => -49.280366, 'longitude' => 69.348557], // French Southern Territories
            ['id' => 73, 'latitude' => -0.803689, 'longitude' => 11.609444], // Gabon
            ['id' => 74, 'latitude' => 13.443182, 'longitude' => -15.310139], // Gambia
            ['id' => 75, 'latitude' => 42.315407, 'longitude' => 43.356892], // Georgia
            ['id' => 76, 'latitude' => 51.165691, 'longitude' => 10.451526], // Germany
            ['id' => 77, 'latitude' => 7.946527, 'longitude' => -1.023194], // Ghana
            ['id' => 78, 'latitude' => 36.137741, 'longitude' => -5.345374], // Gibraltar
            ['id' => 79, 'latitude' => 39.074208, 'longitude' => 21.824312], // Greece
            ['id' => 80, 'latitude' => 71.706936, 'longitude' => -42.604303], // Greenland
            ['id' => 81, 'latitude' => 12.116500, 'longitude' => -61.677000], // Grenada
            ['id' => 82, 'latitude' => 16.265000, 'longitude' => -61.551000], // Guadeloupe
            ['id' => 83, 'latitude' => 13.444304, 'longitude' => 144.793731], // Guam
            ['id' => 84, 'latitude' => 15.783471, 'longitude' => -90.230759], // Guatemala
            ['id' => 85, 'latitude' => 49.465691, 'longitude' => -2.585278], // Guernsey
            ['id' => 86, 'latitude' => 9.945587, 'longitude' => -9.696645], // Guinea
            ['id' => 87, 'latitude' => 11.803749, 'longitude' => -15.180413], // Guinea-Bissau
            ['id' => 88, 'latitude' => 4.860416, 'longitude' => -58.930180], // Guyana
            ['id' => 89, 'latitude' => 18.971187, 'longitude' => -72.285215], // Haiti
            ['id' => 90, 'latitude' => -53.081810, 'longitude' => 73.504158], // Heard Island and McDonald Islands
            ['id' => 91, 'latitude' => 15.199999, 'longitude' => -86.241905], // Honduras
            ['id' => 92, 'latitude' => 22.396428, 'longitude' => 114.109497], // Hong Kong
            ['id' => 93, 'latitude' => 47.162494, 'longitude' => 19.503304], // Hungary
            ['id' => 94, 'latitude' => 64.963051, 'longitude' => -19.020835], // Iceland
            ['id' => 95, 'latitude' => 20.593684, 'longitude' => 78.962880], // India
            ['id' => 96, 'latitude' => -0.789275, 'longitude' => 113.921327], // Indonesia
            ['id' => 97, 'latitude' => 32.427908, 'longitude' => 53.688046], // Iran
            ['id' => 98, 'latitude' => 33.223191, 'longitude' => 43.679291], // Iraq
            ['id' => 99, 'latitude' => 53.412910, 'longitude' => -8.243890], // Ireland
            ['id' => 100, 'latitude' => 54.236107, 'longitude' => -4.548056], // Isle of Man
            ['id' => 101, 'latitude' => 31.046051, 'longitude' => 34.851612], // Israel
            ['id' => 102, 'latitude' => 41.871940, 'longitude' => 12.567380], // Italy
            ['id' => 103, 'latitude' => 18.109581, 'longitude' => -77.297508], // Jamaica
            ['id' => 104, 'latitude' => 36.204824, 'longitude' => 138.252924], // Japan
            ['id' => 105, 'latitude' => 49.214439, 'longitude' => -2.131250], // Jersey
            ['id' => 106, 'latitude' => 31.952162, 'longitude' => 35.233154], // Jordan
            ['id' => 107, 'latitude' => 48.019573, 'longitude' => 66.923684], // Kazakhstan
            ['id' => 108, 'latitude' => -0.023559, 'longitude' => 37.906193], // Kenya
            ['id' => 109, 'latitude' => 1.831239, 'longitude' => -157.376837], // Kiribati
            ['id' => 110, 'latitude' => 29.311660, 'longitude' => 47.481766], // Kuwait
            ['id' => 111, 'latitude' => 41.204380, 'longitude' => 74.766098], // Kyrgyzstan
            ['id' => 112, 'latitude' => 19.856270, 'longitude' => 102.495496], // Laos
            ['id' => 113, 'latitude' => 56.879635, 'longitude' => 24.603189], // Latvia
            ['id' => 114, 'latitude' => 33.854721, 'longitude' => 35.862285], // Lebanon
            ['id' => 115, 'latitude' => -29.609988, 'longitude' => 28.233608], // Lesotho
            ['id' => 116, 'latitude' => 6.428055, 'longitude' => -9.429499], // Liberia
            ['id' => 117, 'latitude' => 26.335100, 'longitude' => 17.228331], // Libya
            ['id' => 118, 'latitude' => 47.141000, 'longitude' => 9.520930], // Liechtenstein
            ['id' => 119, 'latitude' => 55.169438, 'longitude' => 23.881275], // Lithuania
            ['id' => 120, 'latitude' => 49.815273, 'longitude' => 6.129583], // Luxembourg
            ['id' => 121, 'latitude' => 22.198745, 'longitude' => 113.543873], // Macau
            ['id' => 122, 'latitude' => 41.608635, 'longitude' => 21.745275], // Macedonia
            ['id' => 123, 'latitude' => -18.766947, 'longitude' => 46.869107], // Madagascar
            ['id' => 124, 'latitude' => -13.254308, 'longitude' => 34.301525], // Malawi
            ['id' => 125, 'latitude' => 4.210484, 'longitude' => 101.975766], // Malaysia
            ['id' => 126, 'latitude' => 3.202778, 'longitude' => 73.220680], // Maldives
            ['id' => 127, 'latitude' => 17.570692, 'longitude' => -3.996166], // Mali
            ['id' => 128, 'latitude' => 35.937496, 'longitude' => 14.375416], // Malta
            ['id' => 129, 'latitude' => 7.131474, 'longitude' => 171.184478], // Marshall Islands
            ['id' => 130, 'latitude' => 14.641528, 'longitude' => -61.024174], // Martinique
            ['id' => 131, 'latitude' => 21.007890, 'longitude' => -10.940835], // Mauritania
            ['id' => 132, 'latitude' => -20.348404, 'longitude' => 57.552152], // Mauritius
            ['id' => 133, 'latitude' => -12.827500, 'longitude' => 45.166244], // Mayotte
            ['id' => 134, 'latitude' => 23.634501, 'longitude' => -102.552784], // Mexico
            ['id' => 135, 'latitude' => 6.876991, 'longitude' => 158.204080], // Micronesia
            ['id' => 136, 'latitude' => 47.411631, 'longitude' => 28.369885], // Moldova
            ['id' => 137, 'latitude' => 43.738417, 'longitude' => 7.424615], // Monaco
            ['id' => 138, 'latitude' => 46.862496, 'longitude' => 103.846656], // Mongolia
            ['id' => 139, 'latitude' => 42.708678, 'longitude' => 19.374390], // Montenegro
            ['id' => 140, 'latitude' => 16.742498, 'longitude' => -62.187366], // Montserrat
            ['id' => 141, 'latitude' => 31.791702, 'longitude' => -7.092620], // Morocco
            ['id' => 142, 'latitude' => -18.665695, 'longitude' => 35.529562], // Mozambique
            ['id' => 143, 'latitude' => 21.913965, 'longitude' => 95.955974], // Myanmar
            ['id' => 144, 'latitude' => -22.957640, 'longitude' => 18.490410], // Namibia
            ['id' => 145, 'latitude' => -0.522778, 'longitude' => 166.931503], // Nauru
            ['id' => 146, 'latitude' => 28.394857, 'longitude' => 84.124008], // Nepal
            ['id' => 147, 'latitude' => 52.132633, 'longitude' => 5.291266], // Netherlands
            ['id' => 148, 'latitude' => -22.275801, 'longitude' => 166.457144], // New Caledonia
            ['id' => 149, 'latitude' => -40.900557, 'longitude' => 174.885971], // New Zealand
            ['id' => 150, 'latitude' => 12.865416, 'longitude' => -85.207229], // Nicaragua
            ['id' => 151, 'latitude' => 17.357822, 'longitude' => 8.599604], // Niger
            ['id' => 152, 'latitude' => 9.081999, 'longitude' => 8.675277], // Nigeria
            ['id' => 153, 'latitude' => -19.054445, 'longitude' => -169.867233], // Niue
            ['id' => 154, 'latitude' => -29.040835, 'longitude' => 167.954712], // Norfolk Island
            ['id' => 155, 'latitude' => 40.339852, 'longitude' => 127.510093], // North Korea
            ['id' => 156, 'latitude' => 15.097900, 'longitude' => 145.673900], // Northern Mariana Islands
            ['id' => 157, 'latitude' => 60.472024, 'longitude' => 8.468946], // Norway
            ['id' => 158, 'latitude' => 21.512583, 'longitude' => 55.923255], // Oman
            ['id' => 159, 'latitude' => 30.375321, 'longitude' => 69.345116], // Pakistan
            ['id' => 160, 'latitude' => 7.514980, 'longitude' => 134.582520], // Palau
            ['id' => 161, 'latitude' => 31.952162, 'longitude' => 35.233154], // Palestine
            ['id' => 162, 'latitude' => 8.537981, 'longitude' => -80.782127], // Panama
            ['id' => 163, 'latitude' => -6.314993, 'longitude' => 143.955550], // Papua New Guinea
            ['id' => 164, 'latitude' => -23.442503, 'longitude' => -58.443832], // Paraguay
            ['id' => 165, 'latitude' => -9.189967, 'longitude' => -75.015152], // Peru
            ['id' => 166, 'latitude' => 12.879721, 'longitude' => 121.774017], // Philippines
            ['id' => 167, 'latitude' => -25.066667, 'longitude' => -130.101778], // Pitcairn Islands
            ['id' => 168, 'latitude' => 51.919438, 'longitude' => 19.145136], // Poland
            ['id' => 169, 'latitude' => 39.399872, 'longitude' => -8.224454], // Portugal
            ['id' => 170, 'latitude' => 18.220833, 'longitude' => -66.590149], // Puerto Rico
            ['id' => 171, 'latitude' => 25.354826, 'longitude' => 51.183884], // Qatar
            ['id' => 172, 'latitude' => -21.115141, 'longitude' => 55.536384], // Réunion
            ['id' => 173, 'latitude' => 45.943161, 'longitude' => 24.966760], // Romania
            ['id' => 174, 'latitude' => 61.524010, 'longitude' => 105.318756], // Russia
            ['id' => 175, 'latitude' => -1.940278, 'longitude' => 29.873888], // Rwanda
            ['id' => 176, 'latitude' => 17.900000, 'longitude' => -62.833333], // Saint Barthélemy
            ['id' => 177, 'latitude' => -15.939000, 'longitude' => -5.708924], // Saint Helena
            ['id' => 178, 'latitude' => 17.357822, 'longitude' => -62.782998], // Saint Kitts and Nevis
            ['id' => 179, 'latitude' => 13.909444, 'longitude' => -60.978893], // Saint Lucia
            ['id' => 180, 'latitude' => 18.070829, 'longitude' => -63.050081], // Saint Martin
            ['id' => 181, 'latitude' => 46.941936, 'longitude' => -56.271110], // Saint Pierre and Miquelon
            ['id' => 182, 'latitude' => 12.984305, 'longitude' => -61.287228], // Saint Vincent and the Grenadines
            ['id' => 183, 'latitude' => -13.759029, 'longitude' => -172.104629], // Samoa
            ['id' => 184, 'latitude' => 43.942360, 'longitude' => 12.457777], // San Marino
            ['id' => 185, 'latitude' => 0.186360, 'longitude' => 6.613081], // São Tomé and Príncipe
            ['id' => 186, 'latitude' => 23.885942, 'longitude' => 45.079162], // Saudi Arabia
            ['id' => 187, 'latitude' => 14.497401, 'longitude' => -14.452362], // Senegal
            ['id' => 188, 'latitude' => 44.016521, 'longitude' => 21.005859], // Serbia
            ['id' => 189, 'latitude' => -4.679574, 'longitude' => 55.491977], // Seychelles
            ['id' => 190, 'latitude' => 8.619543, 'longitude' => -13.254308], // Sierra Leone
            ['id' => 191, 'latitude' => 1.352083, 'longitude' => 103.819836], // Singapore
            ['id' => 192, 'latitude' => 18.042500, 'longitude' => -63.054830], // Sint Maarten
            ['id' => 193, 'latitude' => 48.669026, 'longitude' => 19.699024], // Slovakia
            ['id' => 194, 'latitude' => 46.151241, 'longitude' => 14.995463], // Slovenia
            ['id' => 195, 'latitude' => -9.645710, 'longitude' => 160.156194], // Solomon Islands
            ['id' => 196, 'latitude' => 2.046934, 'longitude' => 45.318162], // Somalia
            ['id' => 197, 'latitude' => -30.559482, 'longitude' => 22.937506], // South Africa
            ['id' => 198, 'latitude' => -54.429579, 'longitude' => -36.587909], // South Georgia
            ['id' => 199, 'latitude' => 35.907757, 'longitude' => 127.766922], // South Korea
            ['id' => 200, 'latitude' => 6.911806, 'longitude' => 31.246389], // South Sudan
            ['id' => 201, 'latitude' => 40.463667, 'longitude' => -3.749220], // Spain
            ['id' => 202, 'latitude' => 7.873054, 'longitude' => 80.771797], // Sri Lanka
            ['id' => 203, 'latitude' => 12.862807, 'longitude' => 30.217636], // Sudan
            ['id' => 204, 'latitude' => 3.919305, 'longitude' => -56.027783], // Suriname
            ['id' => 205, 'latitude' => 77.553604, 'longitude' => 23.670272], // Svalbard and Jan Mayen
            ['id' => 206, 'latitude' => -26.522503, 'longitude' => 31.465866], // Swaziland
            ['id' => 207, 'latitude' => 60.128161, 'longitude' => 18.643501], // Sweden
            ['id' => 208, 'latitude' => 46.818188, 'longitude' => 8.227512], // Switzerland
            ['id' => 209, 'latitude' => 34.802075, 'longitude' => 38.996815], // Syria
            ['id' => 210, 'latitude' => 23.697810, 'longitude' => 120.960515], // Taiwan
            ['id' => 211, 'latitude' => 38.861034, 'longitude' => 71.276093], // Tajikistan
            ['id' => 212, 'latitude' => -6.369028, 'longitude' => 34.888822], // Tanzania
            ['id' => 213, 'latitude' => 15.870032, 'longitude' => 100.992541], // Thailand
            ['id' => 214, 'latitude' => -8.874217, 'longitude' => 125.727539], // Timor-Leste
            ['id' => 215, 'latitude' => 8.967363, 'longitude' => 1.221537], // Togo
            ['id' => 216, 'latitude' => -8.967363, 'longitude' => -171.855881], // Tokelau
            ['id' => 217, 'latitude' => -21.178986, 'longitude' => -175.198242], // Tonga
            ['id' => 218, 'latitude' => 10.691803, 'longitude' => -61.222503], // Trinidad and Tobago
            ['id' => 219, 'latitude' => 33.886917, 'longitude' => 9.537499], // Tunisia
            ['id' => 220, 'latitude' => 38.963745, 'longitude' => 35.243322], // Turkey
            ['id' => 221, 'latitude' => 38.969719, 'longitude' => 59.556278], // Turkmenistan
            ['id' => 222, 'latitude' => 21.694025, 'longitude' => -71.797928], // Turks and Caicos Islands
            ['id' => 223, 'latitude' => -7.109535, 'longitude' => 177.649330], // Tuvalu
            ['id' => 224, 'latitude' => 1.373333, 'longitude' => 32.290275], // Uganda
            ['id' => 225, 'latitude' => 48.379433, 'longitude' => 31.165580], // Ukraine
            ['id' => 226, 'latitude' => 23.424076, 'longitude' => 53.847818], // United Arab Emirates
            ['id' => 227, 'latitude' => 55.378051, 'longitude' => -3.435973], // United Kingdom
            ['id' => 228, 'latitude' => 37.090240, 'longitude' => -95.712891], // United States
            ['id' => 229, 'latitude' => 19.282319, 'longitude' => 166.647047], // United States Minor Outlying Islands
            ['id' => 230, 'latitude' => -32.522779, 'longitude' => -55.765835], // Uruguay
            ['id' => 231, 'latitude' => 41.377005, 'longitude' => 64.585262], // Uzbekistan
            ['id' => 232, 'latitude' => -17.713371, 'longitude' => 168.315022], // Vanuatu
            ['id' => 233, 'latitude' => 41.902783, 'longitude' => 12.453389], // Vatican City
            ['id' => 234, 'latitude' => 10.480593, 'longitude' => -66.903606], // Venezuela
            ['id' => 235, 'latitude' => 21.027763, 'longitude' => 105.834160], // Vietnam
            ['id' => 236, 'latitude' => 18.431383, 'longitude' => -64.623050], // Virgin Islands (British)
            ['id' => 237, 'latitude' => 18.335765, 'longitude' => -64.896335], // Virgin Islands (U.S.)
            ['id' => 238, 'latitude' => -13.768752, 'longitude' => -177.156097], // Wallis and Futuna
            ['id' => 239, 'latitude' => 24.215527, 'longitude' => -12.885834], // Western Sahara
            ['id' => 240, 'latitude' => 15.552727, 'longitude' => 48.516388], // Yemen
            ['id' => 241, 'latitude' => -15.376706, 'longitude' => 28.223197], // Zambia
            ['id' => 242, 'latitude' => -17.825165, 'longitude' => 31.053028], // Zimbabwe
            ['id' => 243, 'latitude' => 39.904211, 'longitude' => 116.407395], // China
            ['id' => 244, 'latitude' => 25.032969, 'longitude' => 55.166667], // United Arab Emirates
            ['id' => 245, 'latitude' => -35.280937, 'longitude' => 149.130009] // Australia
        ];

        foreach ($updates as $update) {
            DB::table('countries')
                ->where('id', $update['id'])
                ->update([
                    'latitude' => $update['latitude'],
                    'longitude' => $update['longitude']
                ]);
        }
    }
}
