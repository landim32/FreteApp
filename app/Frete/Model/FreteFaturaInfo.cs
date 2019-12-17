using System;
using Newtonsoft.Json;

namespace Frete.Model
{
    public class FreteFaturaInfo
    {
        [JsonProperty("id_fatura")]
        public int Id { get; set; }

        [JsonProperty("id_usuario")]
        public int IdUsuario { get; set; }

        [JsonProperty("id_frete")]
        public int IdFrete { get; set; }

        [JsonProperty("preco")]
        public double Preco { get; set; }

        [JsonProperty("data_inclusao")]
        public DateTime? DataInclusao { get; set; }

        [JsonProperty("data_inclusao_str")]
        public string DataInclusaoLbl { get; set; }

        [JsonProperty("ultima_alteracao")]
        public DateTime? DataAlteracao { get; set; }

        [JsonProperty("ultima_alteracao_str")]
        public string DataAlteracaoLbl { get; set; }

        [JsonProperty("data_vencimento")]
        public DateTime? DataVencimento { get; set; }

        [JsonProperty("data_vencimento_str")]
        public string DataVencimentoLbl { get; set; }

        [JsonProperty("data_pagamento")]
        public DateTime? DataPagamento { get; set; }

        [JsonProperty("data_pagamento_str")]
        public string DataPagamentoLbl { get; set; }

        [JsonProperty("data_confirmacao")]
        public DateTime? DataConfirmacao { get; set; }

        [JsonProperty("data_confirmacao_str")]
        public string DataConfirmacaoLbl { get; set; }

        [JsonProperty("forma_pagamento")]
        public string FormaPagamento { get; set; }

        [JsonProperty("observacao")]
        public string Observacao { get; set; }

        [JsonProperty("url")]
        public string Url { get; set; }

        [JsonProperty("cod_situacao")]
        public string CodSituacao { get; set; }

    }
}
