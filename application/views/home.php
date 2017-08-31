<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="دانلود کتب و جزوات درسی | دکتر امیر فرید امینیان مدرس | عضو هیئت علمی دانشگاه صنعتی سجاد">
	<meta name="author" content="Ali Gandomi | gandomi110@gmail.com">

	<title><?=$this->lang->line('brand')?></title>

	<link rel="icon" href="<?=base_url("assets/img/favicon.ico")?>" type="image/ico">

	<!-- PACE JS -->
	<link href="<?=base_url("assets/css/pace-theme-flash.css")?>" rel="stylesheet">
	<script src="<?=base_url("assets/js/pace.min.js")?>"></script>

	<!-- Bootstrap Core CSS - Uses Bootswatch Flatly Theme: http://bootswatch.com/flatly/ -->
	<link href="<?=base_url("assets/css/bootstrap.min.css")?>" rel="stylesheet"><?=base_url("assets/")?>

	<!-- Custom CSS -->
	<link href="<?=base_url("assets/css/freelancer.css")?>" rel="stylesheet">

	<!-- Custom Fonts -->
	<link href="<?=base_url("assets/font-awesome/css/font-awesome.min.css")?>" rel="stylesheet" type="text/css">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="<?=base_url("assets/js/html5shiv.js")?>"></script>
	<script src="<?=base_url("assets/js/respond.min.js")?>"></script>
	<![endif]-->

</head>

<body id="page-top" class="index">

<!-- Navigation -->
<nav class="navbar navbar-default navbar-fixed-top">
	<div class="container">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header page-scroll">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#page-top"><?=$this->lang->line('brand')?></a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav navbar-right r2l">
				<li class="hidden">
					<a href="#page-top"></a>
				</li>

				<?php

				$cntr = count($all);

				if($cntr)
				{
					if($cntr > 3): // dropdown list
						?>
						<li class="dropdown hidden-xs">
							<a href="#" data-toggle="dropdown" class="dropdown-toggle"><span class="caret"></span>
							</a>
							<ul class="dropdown-menu dropdown-menu-right r2l">

								<?php
								for($i = 3; $i < $cntr; $i++):
									?>
									<li class="page-scroll">
										<a href="#course<?=$all[$i]['id']?>"><?=$all[$i]['name']?></a>
									</li>
								<?php endfor;
								?>

							</ul>
						</li>
					<?php endif;

					for($i = ($cntr > 2 ? 2 : $cntr - 1); $i >= 0; $i--): // three nav item
						?>
						<li class="page-scroll hidden-xs">
							<a href="#course<?=$all[$i]['id']?>"><?=$all[$i]['name']?></a>
						</li>
					<?php endfor;
				}

				for($i = 0; $i < $cntr; $i++): // xs list item
					?>
					<li class="page-scroll visible-xs">
						<a href="#course<?=$all[$i]['id']?>"><?=$all[$i]['name']?></a>
					</li>
				<?php endfor; ?>

			</ul>
		</div>
		<!-- /.navbar-collapse -->
	</div>
	<!-- /.container-fluid -->
</nav>

<!-- Header -->
<header>
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<img class="img-responsive img-circle" src="<?=base_url("assets/img/online.png")?>" alt="">
				<div class="intro-text">
					<span class="name"><?=$this->lang->line('description')?></span>
					<hr class="star-light">
					<span class="skills"><?=$this->lang->line('ostad')." - ".$this->lang->line('sadjad')?></span><br/>
				</div>
			</div>
		</div>
	</div>
</header>

<!-- Courses Section -->
<?php
for($i = 0; $i < $cntr; $i++): // xs list item
	?>

	<section id="course<?=$all[$i]['id']?>" <?=($i % 2 ? 'style="background-color: #f3f3f3"' : '');?>>
		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center">
					<h2 dir="rtl"><?=$all[$i]['name']?></h2>
					<hr class="star-<?=($i % 2 ? 'dark' : 'primary');?>">
				</div>
			</div>
			<div class="row">
				<div class="panel panel-success">
					<div class="panel-body">
						<table class="table table-responsive table-striped table-bordered registered">
							<thead>
							<tr>
								<th>#</th>
								<th class="col-md-6 col-sm-5 col-xs-5">name</th>
								<th class="col-md-1 col-sm-1 col-xs-1">size</th>
								<th class="col-md-2 col-sm-3 col-xs-3">Date</th>
								<th class="col-md-3 col-sm-3 col-xs-3">Description</th>
							</tr>
							</thead>
							<tbody>
							<?php
							$this->load->helper('number');
							$k = 1;
							$files = $all[$i]['files'];
							$filesCntr = count($files);
							for($j = 0; $j < $filesCntr; $j++):
								?>
								<tr>
									<td><?=$k++?></td>
									<td><a class="downloadCntr" href="<?=base_url("download/entry/course".$all[$i]['id']."/".$files[$j]['name'])?>" target="_blank"><?=$files[$j]['name']?></a> &nbsp;&nbsp;<span class="badge pull-right hidden"><?=$files[$j]['download']?></span></td>
									<td><?=byte_format($files[$j]['size'], 1)?></td>
									<td><?php
										$timestamp = explode(' ', $files[$j]['created_at']);
										$date = explode('-', $timestamp[0]);
										echo $this->jdf->gregorian_to_jalali($date[0], $date[1], $date[2], ' - ');
										?></td>
									<td><?=$files[$j]['description']?></td>
								</tr>
							<?php endfor;
							if($k == 1):
								?>
								<tr>
									<td colspan="5"><?=$this->lang->line('filesNotFound')?></td>
								</tr>
							<?php endif ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>

<?php endfor; ?>

<!-- Footer -->
<footer class="text-center" >
	<div class="footer-below">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<span class="pull-left"><span style='font-family:sans-serif'>&copy;</span> 2015<?php date_default_timezone_set("Asia/Tehran"); if(date("Y") != "2015") echo " - ".date("Y")."&nbsp;"; else echo " -";?> Sadjad University of Technology</span>
					<span class="pull-right">Programmer: <?=safe_mailto("gandomi110@gmail.com", "Ali Gandomi")?></span>
				</div>
			</div>
		</div>
	</div>
</footer>

<!-- Scroll to Top Button (Only visible on small and extra-small screen sizes) -->
<div class="scroll-top page-scroll" style="display: none">
	<a class="btn btn-primary" href="#page-top">
		<i class="fa fa-chevron-up"></i>
	</a>
</div>

<!-- jQuery -->
<script src="<?=base_url("assets/js/jquery.js")?>"></script>

<!-- Bootstrap Core JavaScript -->
<script src="<?=base_url("assets/js/bootstrap.min.js")?>"></script>

<!-- Plugin JavaScript -->
<script src="<?=base_url("assets/js/jquery.easing.min.js")?>"></script>
<script src="<?=base_url("assets/js/classie.js")?>"></script>
<script src="<?=base_url("assets/js/cbpAnimatedHeader.js")?>"></script>

<!-- Contact Form JavaScript -->
<script src="<?=base_url("assets/js/jqBootstrapValidation.js")?>"></script>
<script src="<?=base_url("assets/js/contact_me.js")?>"></script>

<!-- Custom Theme JavaScript -->
<script src="<?=base_url("assets/js/freelancer.js")?>"></script>

<script src="<?=base_url("assets/js/home.js")?>"></script>

</body>

</html>
