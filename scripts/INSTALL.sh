#!/bin/bash

if [ ! -f .env.local ]; then
  echo "File .env.local not exist"
  exit 1
fi

symfony console doctrine:database:drop --force
symfony console doctrine:database:create
echo | symfony console doctrine:migrations:migrate
# Dodanie użytkowników
symfony console app:add:user "Damian" "Mosiński" "mosinskidamian11@gmail.com" "zaq12wsx"
symfony console app:add:user "Kamil" "Kowalski" "mkamilKol@gmail.com" "zaq12wsx"
symfony console app:add:user "Asia" "Jakaś" "asJAsk@gmail.com" "zaq12wsx"
# Książki 1 usera
symfony console app:add:user:book "mosinskidamian11@gmail.com" "title1" "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec volutpat ultrices enim placerat molestie. Phasellus a suscipit lacus. Integer faucibus ornare turpis, eget sollicitudin erat auctor et." "9992332323112"
symfony console app:add:user:book "mosinskidamian11@gmail.com" "title2" "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec volutpat ultrices enim placerat molestie. Phasellus a suscipit lacus. Integer faucibus ornare turpis, eget sollicitudin erat auctor et." "9992332323113"
symfony console app:add:user:book "mosinskidamian11@gmail.com" "title3" "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec volutpat ultrices enim placerat molestie. Phasellus a suscipit lacus. Integer faucibus ornare turpis, eget sollicitudin erat auctor et." "9992332323114"
symfony console app:add:user:book "mosinskidamian11@gmail.com" "title4" "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec volutpat ultrices enim placerat molestie. Phasellus a suscipit lacus. Integer faucibus ornare turpis, eget sollicitudin erat auctor et." "9992332323115"
# Książki 2 usera
symfony console app:add:user:book "mkamilKol@gmail.com" "title5" "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec volutpat ultrices enim placerat molestie. Phasellus a suscipit lacus. Integer faucibus ornare turpis, eget sollicitudin erat auctor et." "9992332323212"
symfony console app:add:user:book "mkamilKol@gmail.com" "title6" "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec volutpat ultrices enim placerat molestie. Phasellus a suscipit lacus. Integer faucibus ornare turpis, eget sollicitudin erat auctor et." "9992332323313"
symfony console app:add:user:book "mkamilKol@gmail.com" "title7" "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec volutpat ultrices enim placerat molestie. Phasellus a suscipit lacus. Integer faucibus ornare turpis, eget sollicitudin erat auctor et." "9992332323414"
symfony console app:add:user:book "mkamilKol@gmail.com" "title8" "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec volutpat ultrices enim placerat molestie. Phasellus a suscipit lacus. Integer faucibus ornare turpis, eget sollicitudin erat auctor et." "9992332323515"
# Książki 3 usera
symfony console app:add:user:book "asJAsk@gmail.com" "title9" "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec volutpat ultrices enim placerat molestie. Phasellus a suscipit lacus. Integer faucibus ornare turpis, eget sollicitudin erat auctor et." "9992334323114"
symfony console app:add:user:book "asJAsk@gmail.com" "title10" "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec volutpat ultrices enim placerat molestie. Phasellus a suscipit lacus. Integer faucibus ornare turpis, eget sollicitudin erat auctor et." "9992335323115"
# Książki opinie do książki 1 usera 1
symfony console app:add:book:opinion "title1" 4 "Opinia" "Autor1" "JAsk1@gmail.com"
symfony console app:add:book:opinion "title1" 5 "Opinia" "Autor1" "JAsk1@gmail.com"
# Książki opinie do książki 1 usera 2
symfony console app:add:book:opinion "title5" 6 "Opinia" "Autor1" "JAsk1@gmail.com"
symfony console app:add:book:opinion "title5" 1 "Opinia" "Autor1" "JAsk1@gmail.com"
# Książki opinie do książki 1 usera 3
symfony console app:add:book:opinion "title9" 2 "Opinia" "Autor1" "JAsk1@gmail.com"
symfony console app:add:book:opinion "title9" 8 "Opinia" "Autor1" "JAsk1@gmail.com"