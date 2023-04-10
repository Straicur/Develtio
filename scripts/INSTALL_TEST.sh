#!/bin/bash

pwd

if [ ! -f .env.test.local ]; then
  printf "File .env.test.local not exist\n"
  exit 1
fi

APP_ENV=test symfony console doctrine:database:drop --force
APP_ENV=test symfony console doctrine:database:create
echo | APP_ENV=test symfony console doctrine:migrations:migrate
# Dodanie użytkowników
APP_ENV=test symfony console app:add:user "Damian" "Mosiński" "mosinskidamian11@gmail.com" "Zaq12wsx"
APP_ENV=test symfony console app:add:user "Kamil" "Kowalski" "mkamilKol@gmail.com" "Zaq12wsx"
APP_ENV=test symfony console app:add:user "Asia" "Jakaś" "asJAsk@gmail.com" "Zaq12wsx"
# Książki 1 usera
APP_ENV=test symfony console app:add:user:book "mosinskidamian11@gmail.com" "title1" "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec volutpat ultrices enim placerat molestie. Phasellus a suscipit lacus. Integer faucibus ornare turpis, eget sollicitudin erat auctor et." "9992332323112"
APP_ENV=test symfony console app:add:user:book "mosinskidamian11@gmail.com" "title2" "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec volutpat ultrices enim placerat molestie. Phasellus a suscipit lacus. Integer faucibus ornare turpis, eget sollicitudin erat auctor et." "9992332323113"
APP_ENV=test symfony console app:add:user:book "mosinskidamian11@gmail.com" "title3" "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec volutpat ultrices enim placerat molestie. Phasellus a suscipit lacus. Integer faucibus ornare turpis, eget sollicitudin erat auctor et." "9992332323114"
APP_ENV=test symfony console app:add:user:book "mosinskidamian11@gmail.com" "title4" "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec volutpat ultrices enim placerat molestie. Phasellus a suscipit lacus. Integer faucibus ornare turpis, eget sollicitudin erat auctor et." "9992332323115"
# Książki 2 usera
APP_ENV=test symfony console app:add:user:book "mkamilKol@gmail.com" "title5" "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec volutpat ultrices enim placerat molestie. Phasellus a suscipit lacus. Integer faucibus ornare turpis, eget sollicitudin erat auctor et." "9992332323212"
APP_ENV=test symfony console app:add:user:book "mkamilKol@gmail.com" "title6" "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec volutpat ultrices enim placerat molestie. Phasellus a suscipit lacus. Integer faucibus ornare turpis, eget sollicitudin erat auctor et." "9992332323313"
APP_ENV=test symfony console app:add:user:book "mkamilKol@gmail.com" "title7" "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec volutpat ultrices enim placerat molestie. Phasellus a suscipit lacus. Integer faucibus ornare turpis, eget sollicitudin erat auctor et." "9992332323414"
APP_ENV=test symfony console app:add:user:book "mkamilKol@gmail.com" "title8" "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec volutpat ultrices enim placerat molestie. Phasellus a suscipit lacus. Integer faucibus ornare turpis, eget sollicitudin erat auctor et." "9992332323515"
# Książki 3 usera
APP_ENV=test symfony console app:add:user:book "asJAsk@gmail.com" "title9" "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec volutpat ultrices enim placerat molestie. Phasellus a suscipit lacus. Integer faucibus ornare turpis, eget sollicitudin erat auctor et." "9992334323114"
APP_ENV=test symfony console app:add:user:book "asJAsk@gmail.com" "title10" "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec volutpat ultrices enim placerat molestie. Phasellus a suscipit lacus. Integer faucibus ornare turpis, eget sollicitudin erat auctor et." "9992335323115"
# Książki opinie do książki 1 usera 1
APP_ENV=test symfony console app:add:book:opinion "title1" 4 "Opinia" "Autor1" "JAsk1@gmail.com"
APP_ENV=test symfony console app:add:book:opinion "title1" 5 "Opinia" "Autor1" "JAsk1@gmail.com"
# Książki opinie do książki 1 usera 2
APP_ENV=test symfony console app:add:book:opinion "title5" 6 "Opinia" "Autor1" "JAsk1@gmail.com"
APP_ENV=test symfony console app:add:book:opinion "title5" 1 "Opinia" "Autor1" "JAsk1@gmail.com"
# Książki opinie do książki 1 usera 3
APP_ENV=test symfony console app:add:book:opinion "title9" 2 "Opinia" "Autor1" "JAsk1@gmail.com"
APP_ENV=test symfony console app:add:book:opinion "title9" 8 "Opinia" "Autor1" "JAsk1@gmail.com"