using System;
using Xamarin.Forms.Maps;

namespace EmagineFrete.Model
{
    public class PontoTransporte
    {
        public string Text { get; set; }
        public string TextLabel { get{
                return getTextItem(Tipo) + (Text != "" ? " - " + Text : ""); 
            } set { Text = value; } }
        public string Icone { get {
                return getIconItem(Tipo);
            }}
        public TipoPontoTransporte Tipo { get; set; }
        public Nullable<Position> Posicao { get; set; }

        private string getIconItem(TipoPontoTransporte tipo)
        {
            switch (tipo)
            {
                case TipoPontoTransporte.Carga:
                    return "fa-map-marker";
                case TipoPontoTransporte.Destino:
                    return "fa-flag-checkered";
                case TipoPontoTransporte.Trecho:
                    return "fa-map-marker";
                default:
                    return "fa-plus";
            }
        }
        private string getTextItem(TipoPontoTransporte tipo)
        {
            switch (tipo)
            {
                case TipoPontoTransporte.Carga:
                    return "Origem";
                case TipoPontoTransporte.Destino:
                    return "Destino";
                case TipoPontoTransporte.Trecho:
                    return "Trecho";
                default:
                    return "Adicionar trecho";
            }
        }
    }
    public enum TipoPontoTransporte
    {
        Carga = 0,
        Destino,
        Trecho,
        Add
    }
}
