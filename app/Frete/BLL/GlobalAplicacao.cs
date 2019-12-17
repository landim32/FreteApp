using System;

using Xamarin.Forms;

namespace EmagineFrete.BLL
{
    public static class GlobalAplicacao
    {
        private static AplicacaoEnum _AplicacaoAtual;
        private static string _URLAplicacao;
        public static AplicacaoEnum getAplicacaoAtual(){
            return _AplicacaoAtual;
        }
        public static void setAplicacaoAtual(AplicacaoEnum value){
            _AplicacaoAtual = value;
        }
        public static string getURLAplicacao(){
            return _URLAplicacao;
        }
        public static void setURLAplicacao(string value)
        {
            _URLAplicacao = value;
        }
    }
}

