<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="imagetoolbar" content="no" />
	<title>Demonstração de | FancyBox 1.3.1</title>
    
    <!-- codigo jQuery -->
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="./fancybox/jquery.mousewheel-3.0.2.pack.js"></script>
	<script type="text/javascript" src="./fancybox/jquery.fancybox-1.3.1.js"></script>
	<link rel="stylesheet" type="text/css" href="./fancybox/jquery.fancybox-1.3.1.css" media="screen" />
	
	
	<!-- animações -->
    <script type="text/javascript">
		$(document).ready(function() {
			/*
			*   Examplos - animação inicial
			*/

			$("a#anima1").fancybox({
				'titleShow'		: false
			});

			$("a#anima2").fancybox({
				'titleShow'		: false,
				'transitionIn'	: 'elastic',
				'transitionOut'	: 'elastic'
			});

			$("a#anima3").fancybox({
				'titleShow'		: false,
				'transitionIn'	: 'none',
				'transitionOut'	: 'none'
			});
			
			/*
			*   Examplos - caixa de texto
			*/

			$("a#texto1").fancybox();

			$("a#texto2").fancybox({
				'titlePosition'	: 'inside'
			});

			$("a#texto3").fancybox({
				'titlePosition'	: 'over'
			});
			
			/*
			*   Examplos - galeria
			*/

			$("a[rel=galeria1]").fancybox({
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'titlePosition' 	: 'over',
				'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
					return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
				}
			});
			
			$("#galeria2").click(function() {
                $.fancybox([
                        'imagens/10_b.jpg',
                   		'imagens/11_b.jpg',
                ], {
                        'transitionIn'  : 'elastic',
                        'transitionOut' : 'elastic',
                        'type'          : 'image',
						
                });
        });
	});
	</script>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="content">
	<h1>FancyBox <em>v1.3.1</em></h1>

	<p>Essa é uma página de demonstração.<a href="http://fancybox.net"></a></p>

	<hr />

	<p>
		Tipos de animação - 'fade', 'elastic' e 'none'<br />

		<a id="anima1" href="./imagens/1_b.jpg"><img alt="example1" src="./imagens/1_s.jpg" /></a>

		<a id="anima2" href="./imagens/2_b.jpg"><img alt="example2" src="./imagens/2_s.jpg" /></a>

		<a id="anima3" href="./imagens/3_b.jpg"><img alt="example3" src="./imagens/3_s.jpg" /></a>
	</p>

	<p>
		Diferentes formas de titulo - 'outside', 'inside' and 'over'<br />

		<a id="texto1" href="./imagens/4_b.jpg" title="Lorem ipsum dolor sit amet"><img alt="example4" src="./imagens/4_s.jpg" /></a>

		<a id="texto2" href="./imagens/5_b.jpg" title="Cras neque mi, semper at interdum id, dapibus in leo. Suspendisse nunc leo, eleifend sit amet iaculis et, cursus sed turpis."><img alt="example5" src="./imagens/5_s.jpg" /></a>

		<a id="texto3" href="./imagens/6_b.jpg" title="Sed vel sapien vel sem tempus placerat eu ut tortor. Nulla facilisi. Sed adipiscing, turpis ut cursus molestie, sem eros viverra mauris, quis sollicitudin sapien enim nec est. ras pulvinar placerat diam eu consectetur."><img alt="example6" src="./imagens/6_s.jpg" /></a>
	</p>

	<p>
	  Galleria de imagem (funciona tanto no clique quanto no scroll)<br />

		<a rel="galeria1" href="./imagens/7_b.jpg" title="Lorem ipsum dolor sit amet"><img alt="" src="./imagens/7_s.jpg" /></a>

		<a rel="galeria1" href="./imagens/8_b.jpg" title=""><img alt="" src="./imagens/8_s.jpg" /></a>

		<a rel="galeria1" href="./imagens/9_b.jpg" title=""><img alt="" src="./imagens/9_s.jpg" /></a>
	</p>

	<p>
		Galeria com apenas uma imagem inicial<br />

		<a id="galeria2" href="javascript:;"><img alt="example1" src="./imagens/10_s.jpg" />
        </a>
        
   </p>
</div>
</body>
</html>
