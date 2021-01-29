<?php 

	/**
	 * 
	 */
	class view_admin
	{
		
		function __construct()
		{
			# code...
		}

		/**
		 * Pages
		 */

		public function Home()
		{
			$mod = new model_agence();

			$agences = $mod->GetAll(1, 10);
			?>

			<div class="row justify-content-center">
				<div class="col-md-6">
					<?php $this->ListAgences($agences, 1, 10) ?>
				</div>
				<div class="col-md-6">
					<?php $this->ListLocals(1, false) ?>
				</div>
				<div class="col-md-6 mt-3">
					<?php $this->ListAdmins() ?>
				</div>
			</div>

			<?php
		}

		public function AdminUsers()
		{
			$mod = new model_admin();
			$admin = $mod->DetailAdmin();

			?>

			<div class="row">
				<div class="col-md-6">
					<div class="card">
					    <div class="card-header card-header-tabs card-header-warning">
					      <div class="nav-tabs-navigation">
					        <div class="nav-tabs-wrapper">
					          <span class="nav-tabs-title">Admin:</span>
					          <ul class="nav nav-tabs" data-tabs="tabs">
					            <li class="nav-item">
					              <a class="nav-link active" href="#add" data-toggle="tab">
					                <i class="material-icons">add_box</i> Add
					                <div class="ripple-container"></div>
					              </a>
					            </li>
					            <li class="nav-item">
					              <a class="nav-link" href="#edit" data-toggle="tab">
					                <i class="material-icons">edit</i> Edit
					                <div class="ripple-container"></div>
					              </a>
					            </li>
					            <li class="nav-item">
					              <a class="nav-link" href="#remove" data-toggle="tab">
					                <i class="material-icons">delete</i> Remove
					                <div class="ripple-container"></div>
					              </a>
					            </li>
					          </ul>
					        </div>
					      </div>
					    </div>
					    <div class="card-body">
					      <div class="tab-content">
					        <div class="tab-pane active" id="add">
					          <?php $this->AdminForm(); ?>
					        </div>
					        <div class="tab-pane" id="edit">
					          <?php $this->AdminForm($admin); ?>
					        </div>
					        <div class="tab-pane" id="remove">
					          <h3>Remove it manually from database</h3>
					        </div>
					      </div>
					    </div>
					</div>
				</div>
				<div class="col-md-6">
					<?php $this->ListAdmins(); ?>
				</div>
			</div>

			<?php
		}

		public function Agences($page, $filter, $keyword)
		{
			$mod = new model_agence();

			$limit = 10; // change this var to return more lignes in each page

			$total = ceil($mod->CountSearch($filter, $keyword) / $limit);
			$data = $mod->Search($page, $limit, $filter, $keyword);

			$this->SearchAgenceForm($filter, $keyword);

			$this->ListAgences($data, $total, $page);
		}

		

		/**
		 * Main components
		 */

		public function Header($title = "Dashboard")
		{
			?>

			<!doctype html>
			<html lang="en">

			<head>
			  <title>Soukna-dz | <?php echo $title; ?></title>
			  <!-- Required meta tags -->
			  <meta charset="utf-8">
			  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
			  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
			  <!--     Fonts and icons     -->
			  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
			  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
			  <!-- Material Kit CSS -->
			  <link href="<?php echo(PUBLIC_URL) ?>assets/css/material-dashboard.css?v=2.1.0" rel="stylesheet" />

			  <!--   Core JS Files   -->
			  <script src="<?php echo(PUBLIC_URL) ?>assets/js/core/jquery.min.js"></script>
			  <script src="<?php echo(PUBLIC_URL) ?>assets/js/core/popper.min.js"></script>
			  <script src="<?php echo(PUBLIC_URL) ?>assets/js/core/bootstrap-material-design.min.js"></script>
			  <script src="https://unpkg.com/default-passive-events"></script>
			  <script src="<?php echo(PUBLIC_URL) ?>assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
			  <!-- Place this tag in your head or just before your close body tag. -->
			  <script async defer src="https://buttons.github.io/buttons.js"></script>
			  <!--  Google Maps Plugin    -->
			  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
			  <!-- Chartist JS -->
			  <script src="<?php echo(PUBLIC_URL) ?>assets/js/plugins/chartist.min.js"></script>
			  <!--  Notifications Plugin    -->
			  <script src="<?php echo(PUBLIC_URL) ?>assets/js/plugins/bootstrap-notify.js"></script>
			  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
			  <script src="<?php echo(PUBLIC_URL) ?>assets/js/material-dashboard.js?v=2.1.0"></script>
			  <!-- Material Dashboard DEMO methods, don't include it in your project! -->
			  <script src="<?php echo(PUBLIC_URL) ?>assets/demo/demo.js"></script>
			</head>

			<body class="dark-edition">
			  <div class="wrapper ">

			<?php
		}

		public function SideBar($page = "home")
		{
			?>

			  <div class="sidebar" data-color="orange" data-background-color="black" data-image="./assets/img/sidebar-2.jpg">
			    <!--
			    Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

			    Tip 2: you can also add an image using data-image tag
			-->
			    <div class="logo">
			      <a href="http://www.creative-tim.com" class="simple-text logo-normal">
			        Creative Tim
			      </a>
			    </div>
			    <div class="sidebar-wrapper">
			      <ul class="nav">
			        <li class="nav-item <?php if($page === 'home') echo 'active'; ?>">
			          <a class="nav-link" href="<?php echo(PUBLIC_URL.'admin/') ?>">
			            <i class="material-icons">dashboard</i>
			            <p>Dashboard</p>
			          </a>
			        </li>
			        <li class="nav-item <?php if($page === 'locals') echo 'active'; ?>">
			          <a class="nav-link" href="<?php echo(PUBLIC_URL.'admin/locals') ?>">
			            <i class="material-icons">
			            apartment
			            </i>
			            <p>Locals</p>
			          </a>
			        </li>
			        <li class="nav-item <?php if($page === 'agences') echo 'active'; ?>">
			          <a class="nav-link" href="<?php echo(PUBLIC_URL.'admin/agences') ?>">
			            <i class="material-icons">
			            people
			            </i>
			            <p>Agences</p>
			          </a>
			        </li>
			        <li class="nav-item <?php if($page === 'admins') echo 'active'; ?>">
			          <a class="nav-link" href="<?php echo(PUBLIC_URL.'admin/admins') ?>">
			            <i class="material-icons">
			            admin_panel_settings
			            </i>
			            <p>Admins</p>
			          </a>
			        </li>
			        <!-- your sidebar here -->
			      </ul>
			    </div>
			  </div>

			<?php
		}

		public function Navbar()
		{
			?>

			<div class="main-panel">
			  <!-- Navbar -->
			  <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
			    <div class="container-fluid">
			      <div class="navbar-wrapper">
			        <a class="navbar-brand" href="javascript:void(0)">Dashboard</a>
			      </div>
			      <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
			        <span class="sr-only">Toggle navigation</span>
			        <span class="navbar-toggler-icon icon-bar"></span>
			        <span class="navbar-toggler-icon icon-bar"></span>
			        <span class="navbar-toggler-icon icon-bar"></span>
			      </button>
			      <div class="collapse navbar-collapse justify-content-end">
			        <ul class="navbar-nav">
			          <li class="nav-item">
			            <a class="nav-link" href="<?php echo(PUBLIC_URL.'admin/dc') ?>">
			              <span class="material-icons">
			              exit_to_app
			              </span>
			            </a>
			          </li>
			          <!-- your navbar here -->
			        </ul>
			      </div>
			    </div>
			  </nav>
			  <!-- End Navbar -->
			  <div class="content">
			  	<div class="container-fluid">
			<?php
		}

		public function Footer()
		{
			?>
				</div>
			</div>
			      <footer class="footer">
			        <div class="container-fluid">
			          <nav class="float-left">
			            <ul>
			              <li>
			                <a href="https://www.creative-tim.com">
			                  Creative Tim
			                </a>
			              </li>
			            </ul>
			          </nav>
			          <div class="copyright float-right">
			            &copy;
			            <script>
			              document.write(new Date().getFullYear())
			            </script>, made with <i class="material-icons">favorite</i> by
			            <a href="https://www.creative-tim.com" target="_blank">Creative Tim</a> for a better web.
			          </div>
			          <!-- your footer here -->
			        </div>
			      </footer>
			    </div>
			  </div>
			  
			  <script>
			    $(document).ready(function() {
			      $().ready(function() {
			        $sidebar = $('.sidebar');

			        $sidebar_img_container = $sidebar.find('.sidebar-background');

			        $full_page = $('.full-page');

			        $sidebar_responsive = $('body > .navbar-collapse');

			        window_width = $(window).width();

			        $('.fixed-plugin a').click(function(event) {
			          // Alex if we click on switch, stop propagation of the event, so the dropdown will not be hide, otherwise we set the  section active
			          if ($(this).hasClass('switch-trigger')) {
			            if (event.stopPropagation) {
			              event.stopPropagation();
			            } else if (window.event) {
			              window.event.cancelBubble = true;
			            }
			          }
			        });

			        $('.fixed-plugin .active-color span').click(function() {
			          $full_page_background = $('.full-page-background');

			          $(this).siblings().removeClass('active');
			          $(this).addClass('active');

			          var new_color = $(this).data('color');

			          if ($sidebar.length != 0) {
			            $sidebar.attr('data-color', new_color);
			          }

			          if ($full_page.length != 0) {
			            $full_page.attr('filter-color', new_color);
			          }

			          if ($sidebar_responsive.length != 0) {
			            $sidebar_responsive.attr('data-color', new_color);
			          }
			        });

			        $('.fixed-plugin .background-color .badge').click(function() {
			          $(this).siblings().removeClass('active');
			          $(this).addClass('active');

			          var new_color = $(this).data('background-color');

			          if ($sidebar.length != 0) {
			            $sidebar.attr('data-background-color', new_color);
			          }
			        });

			        $('.fixed-plugin .img-holder').click(function() {
			          $full_page_background = $('.full-page-background');

			          $(this).parent('li').siblings().removeClass('active');
			          $(this).parent('li').addClass('active');


			          var new_image = $(this).find("img").attr('src');

			          if ($sidebar_img_container.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
			            $sidebar_img_container.fadeOut('fast', function() {
			              $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
			              $sidebar_img_container.fadeIn('fast');
			            });
			          }

			          if ($full_page_background.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
			            var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

			            $full_page_background.fadeOut('fast', function() {
			              $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
			              $full_page_background.fadeIn('fast');
			            });
			          }

			          if ($('.switch-sidebar-image input:checked').length == 0) {
			            var new_image = $('.fixed-plugin li.active .img-holder').find("img").attr('src');
			            var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

			            $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
			            $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
			          }

			          if ($sidebar_responsive.length != 0) {
			            $sidebar_responsive.css('background-image', 'url("' + new_image + '")');
			          }
			        });

			        $('.switch-sidebar-image input').change(function() {
			          $full_page_background = $('.full-page-background');

			          $input = $(this);

			          if ($input.is(':checked')) {
			            if ($sidebar_img_container.length != 0) {
			              $sidebar_img_container.fadeIn('fast');
			              $sidebar.attr('data-image', '#');
			            }

			            if ($full_page_background.length != 0) {
			              $full_page_background.fadeIn('fast');
			              $full_page.attr('data-image', '#');
			            }

			            background_image = true;
			          } else {
			            if ($sidebar_img_container.length != 0) {
			              $sidebar.removeAttr('data-image');
			              $sidebar_img_container.fadeOut('fast');
			            }

			            if ($full_page_background.length != 0) {
			              $full_page.removeAttr('data-image', '#');
			              $full_page_background.fadeOut('fast');
			            }

			            background_image = false;
			          }
			        });

			        $('.switch-sidebar-mini input').change(function() {
			          $body = $('body');

			          $input = $(this);

			          if (md.misc.sidebar_mini_active == true) {
			            $('body').removeClass('sidebar-mini');
			            md.misc.sidebar_mini_active = false;

			            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();

			          } else {

			            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');

			            setTimeout(function() {
			              $('body').addClass('sidebar-mini');

			              md.misc.sidebar_mini_active = true;
			            }, 300);
			          }

			          // we simulate the window Resize so the charts will get updated in realtime.
			          var simulateWindowResize = setInterval(function() {
			            window.dispatchEvent(new Event('resize'));
			          }, 180);

			          // we stop the simulation of Window Resize after the animations are completed
			          setTimeout(function() {
			            clearInterval(simulateWindowResize);
			          }, 1000);

			        });
			      });
			    });
			  </script>
			</body>

			</html>

			<?php
		}

		public function Login()
		{
			?>

			      <div class="d-flex justify-content-center align-items-center h-100">
			        <div class="container">
			        	<!-- your content here -->
			    		<form class="card card-profile mx-auto" method="POST" style="max-width: 400px">
			    			  <div class="card-avatar">
			    			    <img class="img" src="http://begmetobuyit.com/application/css/images/noImage.jpg" />
			    			  </div>
			    			  <div class="card-body">
			    			    <div class="form-group">
			    			    	<label class="bmd-label-floating">Username</label>
			    			    	<input class="form-control" type="text" name="username">
			    			    </div>
			    			    <div class="form-group">
			    			    	<label class="bmd-label-floating">Password</label>
			    			    	<input class="form-control" type="password" name="password">
			    			    </div>
			    			    <input type="text" name="tokken" value="<?php echo $_SESSION['tokken'] ?>" hidden>
			    			    <button class="btn btn-warning btn-round">Login</button>
			    			  </div>
			    		</form>
			        </div>
			      </div>
			<?php
		}

		/**
		 * Utility components
		 */

		public function Alert($msg, $type = null)
		{
			switch ($type) {
				case 'error':
					$type = "alert-danger";
					break;
				case 'warning':
					$type = "alert-warning";
					break;
				case 'success':
					$type = "alert-success";
					break;
				case 'info':
					$type = "alert-info";
					break;
				default:
					$type = "alert-primary";
					break;
			}

			?>

			<div class="alert <?php echo($type) ?>">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			    <i class="material-icons">close</i>
			  </button>
			  <span>
			    <b> <?php echo $type; ?>! - </b> <?php echo $msg; ?></span>
			</div>

			<?php
		}

		public function Pagination($url, $current, $total)
		{
			if($total > 1):
			?>

			<nav aria-label="Page navigation example">
			  <ul class="pagination flex-wrap justify-content-center">
			    <li class="page-item <?php if($current == 1) echo('disabled'); ?>">
			      <a class="page-link" href="<?php echo($url . '/' . ($current - 1)); ?>" tabindex="-1">Previous</a>
			    </li>
			    <?php for($i = 1; $i<=$total; $i++): ?>
			    <li class="page-item <?php if($current == $i) echo('active') ?>"><a class="page-link" href="<?php echo($url . '/' . $i) ?>"><?php echo $i; ?></a></li>
				<?php endfor; ?>
			    <li class="page-item <?php if($current == $total) echo('disabled'); ?>">
			      <a class="page-link" href="<?php echo($url . '/' . ($current + 1)); ?>">Next</a>
			    </li>
			  </ul>
			</nav>

			<?php
			endif;
		}

		public function Test()
		{
			?>
			      <div class="content">
			        <div class="container-fluid">
			          <!-- your content here -->
			          <h1 class="text-center">Admin panel testing</h1>
			        </div>
			      </div>
			<?php
		}

		/**
		 * Forms
		 */

		public function SearchAgenceForm($filter, $keyword)
		{
			?>

			<form class="form-inline text-center" method="GET">
				<div class="form-group mx-2">
					<label class="bmd-label-floating">name or email</label>
					<input class="form-control" type="text" name="keyword" value="<?php echo(strip_tags($keyword)); ?>">
				</div>
				<div class="form-group mx-2">
					<select class="form-control" name="filter">
						<option value="all" selected>all</option>
						<option value="pending" <?php if($filter === "pending") echo "selected"; ?> >pending</option>
						<option value="active" <?php if($filter === "active") echo "selected"; ?> >active</option>
						<option value="ban" <?php if($filter === "ban") echo "selected"; ?> >ban</option>
						<option value="desactive" <?php if($filter === "desactive") echo "selected"; ?> >desactive</option>
					</select>
				</div>
				<div class="form-group mx-2">
					<button class="btn btn-warning">Search <i class="material-icons">search</i></button>
				</div>
			</form>

			<?php
		}

		public function AdminForm($data = null)
		{
			?>

			<form method="POST">
				<div class="form-group">
					<label class="bmd-label-floating">username</label>
					<input type="text" class="form-control" name="username" value="<?php if($data) echo($data->username); ?>" >
				</div>
				<div class="row">
					<?php if($data): ?>
					<div class="col-md-6 offset-3">
						<div class="form-group">
							<label class="bmd-label-floating">current password</label>
							<input type="password" class="form-control" name="oldpass">
						</div>
					</div>
					<?php endif; ?>
					<div class="col-md-6">
						<div class="form-group">
							<label class="bmd-label-floating">password</label>
							<input type="password" class="form-control" name="password">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="bmd-label-floating">repeat password</label>
							<input type="password" class="form-control" name="repassword">
						</div>
					</div>
				</div>
				<div class="form-group text-center">
					<button class="btn btn-warning px-4" name="<?php if($data) echo('edit'); else echo('add'); ?>">Add</button>
				</div>
			</form>

			<?php
		}

		/**
		 * Listing
		 */

		public function ListAgences($data, $total, $page)
		{

			?>
				<div class="card">
				  <div class="card-header card-header-warning">
				    <h4 class="card-title ">List Agencies</h4>
				  </div>
				  <?php if($data): ?>
				  <div class="card-body">
				    <div class="table-responsive">
				      <table class="table">
				        <thead class="text-primary">
				          <th>
				            Nom
				          </th>
				          <th>
				            ID
				          </th>
				          <th>
				            Address
				          </th>
				          <th>
				            Telephone
				          </th>
				          <th>
				            Email
				          </th>
				          <th>
				            Etat
				          </th>
				        </thead>
				        <tbody>
				        	<?php foreach($data as $agence): ?>
				        	<tr>
				        		<td >
				        			<a class="d-flex align-items-center" href="<?php echo(PUBLIC_URL.'admin/agence/'.$agence->id_agence); ?>">
				        				<img class="img rounded-circle" src="<?php echo(PUBLIC_URL.'img/'.$agence->Img_prof); ?>" width="50px"/>
				        				<div class="pl-3"><?php echo $agence->nom; ?></div>
				        			</a>
				        		</td>
				        		<td><?php echo $agence->id_agence; ?></td>
				        		<td><?php echo $agence->address; ?></td>
				        		<td><?php echo "$agence->tel1 | $agence->tel2"; ?></td>
				        		<td><?php echo $agence->email; ?></td>
				        		<td>
				        			<?php 
				        				switch ($agence->etat_agence) {
				        					case 'ban':
				        						echo '<span class="text-red">ban</span>';
				        						break;
				        					
				        					case 'active':
				        						echo '<span class="text-success">active</span>';
				        						break;

				        					case 'pending':
				        						echo '<span class="text-primary">pending</span>';
				        						break;

				        					case 'desactive':
				        						echo '<span class="text-warning">desactive</span>';
				        						break;
				        				}

				        			 ?>
				        		</td>
				        	</tr>
				        	<?php endforeach; ?>
				        </tbody>
				      </table>
				    </div>
				  </div>
				  <?php $this->Pagination(PUBLIC_URL."admin/agences",$page, $total); ?>
				  <?php else: ?>
				  	<h3 class="text-center text-warning">No agency found</h3>
				  <?php endif; ?>

				</div>
			<?php
		}

		public function ListLocals($page = 1, $all = true)
		{
			$mod = new model_local();

			$data = $mod->GetAll($page, 10);

			?>
				<div class="card">
				  <div class="card-header card-header-warning">
				    <h4 class="card-title ">List Locals</h4>
				    <p class="card-category"> list all locals</p>
				  </div>
				  <div class="card-body">
				  	<?php if($data): ?>
				    <div class="table-responsive">
				      <table class="table">
				        <thead class=" text-primary">
				          <th>
				            ID
				          </th>
				          <th>
				            Wilaya - Commune
				          </th>
				          <th>
				            Type
				          </th>
				          <th>
				            Prix
				          </th>
				          <th>
				            Etat
				          </th>
				          <th>
				          	Agence
				          </th>
				          <?php if($all): ?>
				          <th>
				          	Action
				          </th>
				      	  <?php endif; ?>
				        </thead>
				        <tbody>
				        	<?php foreach($data as $local): ?>
				        	<tr>
				        		<td><?php echo $local->id_local; ?></td>
				        		<td><?php echo "$local->wilaya - $local->commune"; ?></td>
				        		<td><?php echo ucfirst($local->type); ?></td>
				        		<td><?php echo $local->prix; ?></td>
				        		<td><?php echo $local->etat_local; ?></td>
				        		<td >
				        			<a class="d-flex align-items-center" href="<?php echo(PUBLIC_URL.'admin/agence/'.$local->id_agence) ?>">
				        				<img class="img rounded-circle" src="<?php echo(PUBLIC_URL.'img/'.$local->Img_prof) ?>" width="50px"/>
				        				<div class="pl-3"><?php echo $local->nom; ?></div>
				        			</a>
				        		</td>
				        		<?php if($all): ?>
				        		<td><button class="btn btn-danger" data-toggle="modal" data-target="#exampleModalCenter" data-id="<?php echo($local->id_local) ?>">Delete</button></td>
				        		<?php endif; ?>
				        	</tr>
				        	<?php endforeach; ?>
				        </tbody>
				      </table>
				    </div>
				    <?php else: ?>
				    	<h3 class="text-center">no locals found</h3>
				    <?php endif; ?>
				  </div>

				  <?php 
					  if($all)
					  	$this->Pagination(PUBLIC_URL."admin/locals", $page, ceil($mod->CountAll() / 10)); 
				  ?>
				</div>
			<?php
			$this->DeleteModal();
		}

		public function ListAdmins()
		{
			$mod = new model_admin();

			$data = $mod->ListAdmins();
			?>

			<div class="card">
			  <div class="card-header card-header-warning">
			    <h4 class="card-title ">List Admins</h4>
			  </div>
			  <div class="card-body">
			  	<?php if($data): ?>
			  		<div class="table-responsive">
			  		  <table class="table">
			  		    <thead class=" text-primary">
			  		      <th>
			  		        ID
			  		      </th>
			  		      <th>
			  		        Username
			  		      </th>
			  		      <th>
			  		        Last Login
			  		      </th>
			  		    </thead>
			  		    <tbody>
			  		    	<?php foreach($data as $admin): ?>
			  		    	<tr>
			  		    		<td><?php echo $admin->id_admin; ?></td>
			  		    		<td><?php echo $admin->username; ?></td>
			  		    		<td><?php echo $admin->last_login; ?></td>
			  		    	</tr>
			  		    	<?php endforeach; ?>
			  		    </tbody>
			  		  </table>
			  		</div>
			  	<?php else: ?>
			  		<h3>no admin found which is impossible</h3>
			  	<?php endif; ?>
			    
			  </div>
			</div>

			<?php
		}

		/**
		 * Modals
		 */

		public function AgenceModal()
		{
			# code...
		}

		public function DeleteModal()
		{
			?>
			<!-- delete modal -->
			<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			  <div class="modal-dialog modal-dialog-centered" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="exampleModalCenterTitle">Confirmation</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">
			        are u sure u want to delete this local?
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			        <form method="POST" class="m-2">
			          <input type="number" name="local" id="local" hidden>
			          <button class="btn btn-danger">Yes</button>
			        </form>
			      </div>
			    </div>
			  </div>
			</div>

			<script type="text/javascript">
			  $('#exampleModalCenter').on('show.bs.modal', function (event) {
			    var button = $(event.relatedTarget); // Button that triggered the modal
			    var id = button.data('id');
			    $('#local').val(id);
			  });
			</script>
			<?php
		}

		public function ConfirmModal()
		{
			# code...
		}

		/**
		 * Details
		 */

		public function AgenceDetail($id_agence)
		{
			$mod = new model_agence();
			$data = $mod->Detail($id_agence);

			?>
			<?php if($data): ?>
			<div class="row">
				<div class="col-md-6">
					<div class="card card-profile">
					  <div class="card-avatar">
					    <a href="#pablo">
					      <img class="img" src="<?php echo(PUBLIC_URL."img/".$data->Img_prof); ?>">
					    </a>
					  </div>
					  <div class="card-body">
					    <h6 class="card-category">
					    	Etat: 
					    	<?php 
					    		switch ($data->etat_agence) {
					    			case 'ban':
					    				echo '<span class="text-red">ban</span>';
					    				break;
					    			
					    			case 'active':
					    				echo '<span class="text-success">active</span>';
					    				break;

					    			case 'pending':
					    				echo '<span class="text-primary">pending</span>';
					    				break;

					    			case 'desactive':
					    				echo '<span class="text-warning">desactive</span>';
					    				break;
					    			
					    			case 'refuse':
					    				echo '<span class="text-danger">desactive</span>';
					    				break;
					    		}

					    	 ?>
					    </h6>
					    <h4 class="card-title"><?php echo $data->nom; ?></h4>
					    <div class="table-responsive">
					      <table class="table">
					        <tbody>
					        	<tr>
					        		<td class="">Phone</td>
					        		<td><?php echo "$data->tel1 | $data->tel2"; ?></td>
					        	</tr>
					        	<tr>
					        		<td class="">Address</td>
					        		<td><?php echo $data->address; ?></td>
					        	</tr>
					        	<tr>
					        		<td class="">Email</td>
					        		<td><?php echo $data->email; ?></td>
					        	</tr>
					        	<tr>
					        		<td class="">facebook</td>
					        		<td><a href="<?php echo($data->fb); ?>">Fb Link</a></td>
					        	</tr>
					        </tbody>
					      </table>
					    </div>
					    <form class="d-flex justify-content-around" method="POST">
					    	<div class="form-group mx-2">
					    		<select class="form-control" name="etat">
					    			<option value="pending" <?php if($data->etat_agence === "pending") echo "selected"; ?> >pending</option>
					    			<option value="active" <?php if($data->etat_agence === "active") echo "selected"; ?> >active</option>
					    			<option value="ban" <?php if($data->etat_agence === "ban") echo "selected"; ?> >ban</option>
					    			<option value="desactive" <?php if($data->etat_agence === "desactive") echo "selected"; ?> >desactive</option>
					    			<option value="refuse" <?php if($data->etat_agence === "refuse") echo "selected"; ?> >refuse</option>
					    		</select>
					    	</div>
					    	<div class="form-group mx-2">
					    		<button class="btn btn-warning">Confirm</button>
					    	</div>
					    </form>
					  </div>
					</div>
				</div>
				<div class="col-md-6">
					<?php if(isset($data->registre)): ?>
					<div style="max-height: 300px; overflow: hidden;">
						<img class="img-fluid" src="<?php echo(PUBLIC_URL.'admin/imgregister/'.$data->registre); ?>">
					</div>
					<?php endif; ?>

					<?php if(isset($data->local)): ?>
					<div style="max-height: 300px; overflow: hidden;">
						<img class="img-fluid" src="<?php echo(PUBLIC_URL.'admin/imglocal/'.$data->local); ?>">
					</div>
					<?php endif; ?>
				</div>
			</div>
			<?php else: ?>

			<?php endif; ?>
			<?php
		}

		public function DetailLocal($id_local)
		{
			# code...
		}
	}

 ?>