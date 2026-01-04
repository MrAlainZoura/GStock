import './bootstrap';
import 'flowbite';

window.addEventListener('load', () => {
    document.getElementById('divload').style.display = 'none';

    const formPresenceStore = window.document.getElementById('formPresenceStore');
    const formUpdateCoorGeoDepot = window.document.getElementById('formUpdatePoDep');

   if (formPresenceStore) {
      formPresenceStore.addEventListener('submit', (event) => {
         event.preventDefault(); 
           coord_geo(([lat, lon]) => {
               if(lat == null || lon == null)alert('Impossible de déterminer votre position, autoriser la localisation');
               else formPresenceStore.submit()
            //    console.log("Coordonnées reçues:", lat, lon);
            });
      });
   }
   if (formUpdateCoorGeoDepot) {
      formUpdateCoorGeoDepot.addEventListener('submit', (event) => {
         event.preventDefault(); 
           coord_geo(([lat, lon]) => {
               if(lat == null || lon == null)alert('Impossible de déterminer votre position, autoriser la localisation');
               else formUpdateCoorGeoDepot.submit()
            //    console.log("Coordonnées reçues:", lat, lon);
            });
      });
   }

   const coord_geo = (callback) => {
      navigator.geolocation.getCurrentPosition(
         (pos) => {
            const latitude = pos.coords.latitude;
            const longitude = pos.coords.longitude;

            const latForm = document.getElementById('lat');
            const lonForm = document.getElementById('lon');
            const latAutoForm = document.getElementById('latAuto');
            const lonAutoForm = document.getElementById('lonAuto');

            if (latForm) latForm.value = latitude;
            else console.log("erreur formulaire lat");
            if (latAutoForm) latAutoForm.value = latitude;
            else console.log("erreur formulaire lat");

            if (lonForm) lonForm.value = longitude;
            else console.log("erreur formulaire lon");

            if (lonAutoForm) lonAutoForm.value = longitude;
            else console.log("erreur formulaire lon");

            callback([latitude, longitude]);
         },
         (err) => {
            console.error("Erreur:", err);
            callback([null, null]);
         }
      );
   };
 
});