<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="description" content="Productos del catalogo">
	<link rel="icon" type="image/png" sizes="16x16" href="img/favicon.png">
	<link rel="stylesheet" href="{{ asset('css/public/main.css') }}">
	<link rel="stylesheet" href="{{ asset('css/public/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/public/fancybox.css') }}">

	<title>Marca: {{ $productColorTone->product->trademark->name }} | Ref. {{ "{$productColorTone->product->code}-{$productColorTone->color->code}-{$productColorTone->tone->code}" }}</title>
</head>
<body class="body-catalogo4">

	<div class="container-fluid bcontent log logo">
	    <nav class="navbar navbar-expand-sm">
	        <a class="navbar-brand">
				<img src="{{ asset('images/logo.png') }}" style="width: 100px !important; height: auto !important;"/>
            </a>
	    </nav>
	</div>

	<section>
		<div class="container-fluid  single-product">

            <div class="text-center">
                <p>
                    <b>REF:</b> {{ "{$productColorTone->product->code}-{$productColorTone->color->code}-{$productColorTone->tone->code}" }}</br>
                    <b>MARCA:</b> {{ $productColorTone->product->trademark->name }}
                </p>
            </div>
			<div class="row justify-content-center">
				<p class="imglist">
                    @foreach ($imageFiles as $image)
                        <div class="col-12 col-md-6 col-lg-4 imgprod">
                            <a class="one" href="{{ $image->path }}" data-fancybox="galeria">
                                <img src="{{ $image->path }}" width="100%" />
                            </a>
                        </div>
                    @endforeach
                    @foreach ($videoFiles as $video)
                        <div class="col-12 col-md-6 col-lg-4 imgprod">
                            <a class="one" href="{{ $video->path }}" data-fancybox="galeria">
                                <video controls width="100%">
                                    <source src="{{ $video->path }}" type="video/mp4">
                                    Tu navegador no soporta el elemento de video.
                                </video>
                            </a>
                        </div>
                    @endforeach
				</p>
			</div>
		</div>
	</section>


	<footer class="container-fluid bcontent">

			<div class="col-12 row redess">
				<div class="logg col-12 col-lg-12 ">

					<p class="cr">MARIANGEL FULL MODA SAS © Todos los derechos reservados.</p>
					<p class="mi">Hecho en Colombia</p><img class="banderac" src="{{ asset('images/colombia.png') }}">
				</div>
			</div>

	</footer>
</body>
</html>

