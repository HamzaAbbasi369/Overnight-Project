<!--
/**********************************************************
Virtual Mirror html5, version 1.0
Copyright by Matthias Haase <matthias_haase@bennewitz.com>
last changed 25.07.2021
**********************************************************/
-->
<html>
	<head>
		<title>Virtual Mirror Development Version</title>
		<meta charset='utf-8'>

		<link rel='localization' hreflang='ar' href='../ln/ar.json' type='application/vnd.oftn.l10n+json' />
		<link rel='localization' hreflang='de' href='../ln/de.json' type='application/vnd.oftn.l10n+json' />
		<link rel='localization' hreflang='en' href='../ln/en.json' type='application/vnd.oftn.l10n+json' />
		<link rel='localization' hreflang='es' href='../ln/es.json' type='application/vnd.oftn.l10n+json' />
		<link rel='localization' hreflang='fr' href='../ln/fr.json' type='application/vnd.oftn.l10n+json' />
		<link rel='localization' hreflang='gr' href='../ln/gr.json' type='application/vnd.oftn.l10n+json' />
		<link rel='localization' hreflang='he' href='../ln/he.json' type='application/vnd.oftn.l10n+json' />
		<link rel='localization' hreflang='nl' href='../ln/nl.json' type='application/vnd.oftn.l10n+json' />
		<link rel='localization' hreflang='pt' href='../ln/pt.json' type='application/vnd.oftn.l10n+json' />
		<link rel='localization' hreflang='ro' href='../ln/ro.json' type='application/vnd.oftn.l10n+json' />
		<link rel='localization' hreflang='ru' href='../ln/ru.json' type='application/vnd.oftn.l10n+json' />
		<link rel='localization' hreflang='sl' href='../ln/sl.json' type='application/vnd.oftn.l10n+json' />

		<link href='./styles/vm.min.css' rel='stylesheet' type='text/css' />
		<link href='./styles/toastr.min.css' rel='stylesheet' type='text/css' />
	</head>

	<body>
		<div id='content'>
			<div id='vm-header'>Virtual Mirror</div>
			<div id='main-bar' class='dropdown'>
				<button id='menu-button' class='dropbtn'><img src="media/menu-white.png" width="28" height="28" alt="Virtual Mirror Menu"></button>
				<div id='menu' class='dropdown-content'>
					<a id='usephoto' href='#'>Use Photo</a>
					<a id='loadphoto' href='#' onclick="document.getElementById('filedialog').click();return false">Load Your Own Photo</a>
					<a id='deletephoto' href='#' class='disabled'>Delete Your Own Photo</a>
					<a id='usevideo' href='#'>Use Video</a>
					<a id='stopvideo' href='#'>Stop Video</a>
					<a id='filterbyshape' style='display: none;' href='#'>Preferred</a>
				</div>
			</div>
			<div id='container'>
				<video id='videostream'></video>
				<canvas id='overlay'></canvas>
				<div id='overlay3d'></div>
				<div id='canvasloader-container' class='spinner'></div>
				<button id='savesnap'><img src="media/savesnap.png" width="38" height="38" alt="Save Snapshot"></button>
				<div id='progress'>
					<div id='bar'></div>
				</div>
				<div id='shapedetection' class='noteArea'></div>
				<div id='shapesymbol'></div>
			</div>
			<input type='file' id='filedialog' name='files[]' accept='image/*'>
		</div>

		<script src='./ext_js/utils.js'></script>
		<script src='./ext_js/jquery.min.js'></script>
		<script src='./ext_js/pica.min.js'></script>
		<script src='./ext_js/Blob.min.js'></script>
		<script src='./ext_js/FileSaver.min.js'></script>
		<script src='./ext_js/heartcode-canvasloader-min.js'></script>
		<script src='./ext_js/toastr.min.js'></script>
		<script src='../js/i18n-init.js'></script>
		<script src='../js/i18n.js'></script>
		<script src='../js/tensorflow/tf-core.min.js'></script>
		<script src='../js/tensorflow/tf-converter.min.js'></script>
		<script src='../js/tensorflow/tf-backend-wasm.js'></script>
		<script src='../js/vm-config.js'></script>
		<?php $time = time(); ?>
		<script src="../js/vm.js?ver=$time" type='module'></script>

	</body>
</html>
