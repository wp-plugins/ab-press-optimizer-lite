<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   ab-press-optimizer
 * @author    Ivan Lopez
 * @link      http://ABPressOptimizer.com
 * @copyright 2013 Ivan Lopez
 */
?>
<div class="wrap">
	
	<div class="ab-press-header">
		<img src="<?php echo plugins_url( 'ab-press-optimizer-lite/assets/ab-logo.png') ?>">
	</div>

	<div class="ab-press-banner">
		<a href="http://abpressoptimizer.com?utm_source=abp_lite&utm_medium=upgrade_link _banner&utm_campaign=pro_upgrade" target="_blank">
			<img src="<?php echo plugins_url( 'ab-press-optimizer-lite/assets/plugin-banner.png') ?>">
		</a>
	</div>

	<?php screen_icon('ab-press-optimizer'); ?>

	<h2 class="nav-tab-wrapper">
		<a href="" class="nav-tab nav-tab-active">Experiments</a>
		<a href="admin.php?page=abpo-gettingStarted" class="nav-tab">Getting Started</a>
	</h2>

	<div class="ab-press-nav">
		<a href="<?php bloginfo('wpurl') ?>/wp-admin/admin.php?page=abpo-new" class="button-primary">Add New</a>
		<button class="button" disabled>Export to CSV</button>
		<span class="ab-press-pro-label ">Pro Feature</span>
	</div>



	<?php
		if(isset($_SESSION['message']))
		{
			$message = explode("|", $_SESSION['message']);
			if(count($message) > 1 )
				echo "<div id='message' class=' below-h2 error'><p>".$message[0]."</p></div>";
			else
				echo "<div id='message' class='updated below-h2'><p>".$message[0]."</p></div>";
			ab_press_deleteMessage();
		}
		
	?>

	<?php 
		$pagenum = isset( $_GET['paging'] ) ? absint( $_GET['paging'] ) : 1;
     	$limit = 10;
     	$offset = ( $pagenum - 1 ) * $limit;
		$experiments = ab_press_getAllExperiment($offset, $limit);

		$total = count(ab_press_getAllExperiment());
		$num_of_pages = ceil( $total / $limit );

		$page_links = paginate_links( array(
		    'base' => add_query_arg( 'paging', '%#%' ),
		    'format' => '',
		    'prev_text' => __( '&laquo;', 'aag' ),
		    'next_text' => __( '&raquo;', 'aag' ),
		    'total' => $num_of_pages,
		    'current' => $pagenum
		) );
	?>

	<?php
		foreach (ab_press_getAllActiveExperiments(true) as $experiment) {
			if($experiment->status == "running")
			{
				$featuredExperiment = $experiment;
				break;
			}
		}
	?>
	
	<?php if(isset($featuredExperiment)): ?>
	<div class="ab-current-test">
		<h2>Current Experiment Summary: <a href="admin.php?page=abpo-details&eid=<?php echo $featuredExperiment->id; ?>"><?php echo ucwords($featuredExperiment->name); ?></a> </h2>

		<ul class="ab-press-dashboard">
			<li class="totalVisitore"><span>Total Visitors</span><?php echo number_format($totalVisitor = ab_press_getTotalVisitors($featuredExperiment)); ?></li>
			<li class="convertions"><span>Total Conversions</span><?php echo number_format($totalConvertions = ab_press_getTotalConvertions($featuredExperiment));  ?></li>
			<li class="converstionRate"><span>Total Conversions Rate</span>
				<?php echo ($totalConvertions == 0) ? "0" : ab_press_getConvertionRate($totalConvertions,$totalVisitor);?>%
			</li>
			<li class="variations"><span>Variations</span><?php echo count($featuredExperiment->variations); ?></li>
		</ul>
	</div>
	<?php endif; ?>
	
	<h2>Experiments</h2>

	<table class="widefat">
		<thead>
		    <tr>
		        <th>ID</th>
		        <th>Name</th>
		        <th>Visitors</th>       
		        <th>Conversions</th>
		        <th>Conversions Rate</th>
		        <th>Variations</th>
		        <th>Experiment Date</th>
		        <th>Status</th>
		    </tr>
		</thead>
		<tfoot>
		     <tr>
		        <th>ID</th>
		        <th>Name</th>
		        <th>Visitors</th>       
		        <th>Conversions</th>
		        <th>Conversions Rate</th>
		        <th>Variations</th>
		        <th>Experiment Dates</th>
		        <th>Status</th>
		    </tr>
		</tfoot>
		<tbody>

		<?php foreach ($experiments as $experiment): ?>

		<tr>
			<th><?php echo ucwords($experiment->id); ?></th>
			<th><a href="admin.php?page=abpo-details&eid=<?php echo $experiment->id; ?>"><?php echo ucwords($experiment->name); ?></a></th>
			<th><?php echo number_format($totalVariationVisitor = ab_press_getTotalVisitors($experiment)); ?></th>
			<th><?php echo number_format($totalVariationConvertion = ab_press_getTotalConvertions($experiment));  ?></th>
			<th><?php echo ($totalVariationConvertion == 0) ? "0" : ab_press_getConvertionRate($totalVariationConvertion,$totalVariationVisitor);?>%</th>
			<th><?php echo count($experiment->variations); ?></th>
			<th><?php echo date("m-d-Y", strtotime($experiment->start_date)) ?> - <?php echo date("m-d-Y", strtotime($experiment->end_date)) ?></th>
			<th><?php echo ucwords($experiment->status); ?></th>
		</tr>

		<?php endforeach; ?>
		
		</tbody>
	</table>

	<?php 
		if ( $page_links ) {
    		echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
		}
	?>
</div>