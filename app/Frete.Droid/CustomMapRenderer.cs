using System;
using System.Collections.Generic;
using Android.Content;
using Android.Gms.Maps;
using Android.Gms.Maps.Model;
using Emagine.Mapa.Controls;
using Frete.Droid;
using Xamarin.Forms;
using Xamarin.Forms.Maps;
using Xamarin.Forms.Maps.Android;

[assembly: ExportRenderer(typeof(CustomMap), typeof(CustomMapRenderer))]
namespace Frete.Droid
{
    public class CustomMapRenderer : MapRenderer
    {
        List<Position> routeCoordinates;
        private GoogleMap _map;
        Polyline poli; 

        public CustomMapRenderer(Context context) : base(context)
        {
        }

        public void resetPolyline(List<Position> newRoute)
        {                           
            if(poli != null){
                poli.Remove();
                poli.Dispose();
                var polylineOptions = new PolylineOptions();
                polylineOptions.InvokeColor(0x66FF0000);
                while (polylineOptions.Points != null && polylineOptions.Points.Count > 0)
                {
                    polylineOptions.Points.RemoveAt(0);
                }
                if(newRoute != null){
                    foreach (var position in newRoute)
                    {
                        polylineOptions.Add(new LatLng(position.Latitude, position.Longitude));
                    }
                    poli = NativeMap.AddPolyline(polylineOptions);   
                }
            }
        }

        protected override void OnElementChanged(Xamarin.Forms.Platform.Android.ElementChangedEventArgs<Map> e)
        {
            base.OnElementChanged(e);

            if (e.OldElement != null)
            {
                // Unsubscribe
            }

            if (_map != null)
                _map.MapClick -= googleMap_MapClick;

            if (e.NewElement != null)
            {
                var formsMap = (CustomMap)e.NewElement;
                routeCoordinates = formsMap.RouteCoordinates;
                formsMap.resetPolyline += (sender, eventArg) => { resetPolyline(eventArg); };
                Control.GetMapAsync(this);
            }
        }

        private void googleMap_MapClick(object sender, GoogleMap.MapClickEventArgs e)
        {
            ((CustomMap)Element).OnTap(new Position(e.Point.Latitude, e.Point.Longitude));
        }

        protected override void OnMapReady(GoogleMap map)
        {
            base.OnMapReady(map);

            _map = map;

            if (_map != null)
                _map.MapClick += googleMap_MapClick;

            var polylineOptions = new PolylineOptions();
            polylineOptions.InvokeColor(0x66FF0000);

            foreach (var position in routeCoordinates)
            {
                polylineOptions.Add(new LatLng(position.Latitude, position.Longitude));
            }

            poli = NativeMap.AddPolyline(polylineOptions);
        }
    }
}
