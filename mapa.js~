var mapa;
 
function initialize() {
    var latlng = new google.maps.LatLng(-29.78, 302.88); //centro é aeroporto de Uruguaiana
 
    var opcoes = {
        zoom: 6,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
 
    mapa = new google.maps.Map(document.getElementById("mapa"), opcoes);
				var zoom = mapa.getZoom();
			
}

//=============================================================
function carregarPontos()
 {
 
	$.getJSON('pontos.json', function(pontos)
		{

		$.each(pontos, function(index, ponto) 
			{

			var marcador = new google.maps.Marker(
					{
							position: new google.maps.LatLng(ponto.Latitude, ponto.Longitude),
							title: "Clique no ícone para informações detalahadas.",
							map: mapa,
							icon: ponto.icone
					});
					
				var janelaInfo = new google.maps.InfoWindow(), marcador;
 
				google.maps.event.addListener(marcador, 'click', (function(marcador, i) 
					{
					return function()
						{
        janelaInfo.setContent(ponto.Informacao);
        janelaInfo.open(mapa, marcador);
						}
					})(marcador));

			});

		});
 
	}
initialize();
carregarPontos();
 
