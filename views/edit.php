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
<?php 
		$experiment = ab_press_getExperiment($_GET['eid']);
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
	<h2>Edit Experiment</h2>

	<p>Please fill in all the fields that are marked as required and create at least one variation. If you set your start date to be in the future then the experiment will be set to pause until that date arrives.</p>

	<?php
		if(isset($_SESSION['message']))
		{
			echo "<div id='message' class='error'><p>".$_SESSION['message']."</p></div>";
			ab_press_deleteMessage();
		}
	?>

	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=abpo-edit&eid=<?php echo $_GET['eid'] ?>" method="post" enctype="multipart/form-data" class="ab-press-experimentForm">
		<?php if ( function_exists('wp_nonce_field') ) wp_nonce_field('abpo-new-experiment'); ?>
		<input type="hidden" name="update" value="update">
		<input type="hidden" name="id" value="<?php echo $experiment->id; ?>">
		
		<div class="ab-press-group">
			<label class="ab-press-label" for="name">Experiment Name <span class="description">(required)</span></label>
			<div class="ab-press-controls">
				<input type="text" id="name" name="name" class="regular-text" value="<?php echo $experiment->name; ?>">
			</div>
		</div>

		<div class="ab-press-group">
			<label class="ab-press-label" for="description">Experiment Description</label>
			<div class="ab-press-controls">
				<textarea name="description" id="description" class="regular-text"><?php echo $experiment->description; ?></textarea>
			</div>
		</div>

		<div class="ab-press-group">
			<label class="ab-press-label" for="status">Status</label>
			<div class="ab-press-controls">
				<select name="status" id="status">
					<option value="running" <?php echo ($experiment->status == "running") ? "selected='selected'" : "" ?> >Running</option>
					<option value="paused" <?php echo ($experiment->status == "paused") ? "selected='selected'" : "" ?>>Paused</option>
					<option value="complete" <?php echo ($experiment->status == "complete") ? "selected='selected'" : "" ?>>Complete</option>
				</select>
			</div>
		</div> 

		<div class="ab-press-group">
			<label class="ab-press-label" for="startDate">Start Date <span class="description">(required)</span></label>
			<div class="ab-press-controls">
				<input type="text" name="startDate" id="startDate" class="ab-datepicker" value="<?php echo date("m/d/Y", strtotime($experiment->start_date)); ?>">
			</div>
		</div> 

		<div class="ab-press-group">
			<label class="ab-press-label" for="endDate">End Date <span class="description">(required)</span></label>
			<div class="ab-press-controls">
				<input type="text"  name="endDate" id="endDate"  class="ab-datepicker" value="<?php echo date("m/d/Y", strtotime($experiment->end_date)); ?>">
			</div>
		</div>

		<div class="ab-press-group">
			<label class="ab-press-label" for="goal">Goal <span class="description">(required)</span></label>
			<div class="ab-press-controls">
				<input type="text" id="goal" name="goal" class="regular-text" value="<?php echo $experiment->goal; ?>">
			</div>
		</div>

		<div class="ab-press-group">
			<label class="ab-press-label" for="goalTrigger">Goal Trigger</label>
			<div class="ab-press-controls">
				<select name="goalTrigger" id="goalTrigger" disabled>
					<option value="page" <?php echo ($experiment->goal_type == "page") ? "selected='selected'" : "" ?>>Page View</option>
					<option value="clickEvent" <?php echo ($experiment->goal_type == "clickEvent") ? "selected='selected'" : "" ?>>Click Event</option>
					<option value="form" <?php echo ($experiment->goal_type == "form") ? "selected='selected'" : "" ?>>Submit a Form</option>
				</select>

				<span class="ab-press-pro-label ">Pro Feature</span>
			</div>
		</div>

		<div class="ab-press-group <?php echo ($experiment->goal_type == "clickEvent") ? "ab-no-url-onload" : "" ?>" id="ab-urlGroup">
			<label class="ab-press-label" for="url">URL <span class="description">(required)</span></label>
			<div class="ab-press-controls">

				<select id="url" name="url">
					<option value="" >Select a Page</option>
					<?php 
						foreach( get_post_types( array('public' => true) ) as $post_type ) {
						  if ( in_array( $post_type, array('attachment') ) )
						    continue;
						  	$pt = get_post_type_object( $post_type );
							
							echo "  <optgroup label=".$pt->labels->name.">";

							query_posts('post_type='.$post_type.'&posts_per_page=-1');
							while( have_posts() ) {
								the_post();
								if(get_permalink() == $experiment->url)
									echo "<option value=".get_permalink()." selected='selected'>".get_the_title()."</option>";
								else
									echo "<option value=".get_permalink().">".get_the_title()."</option>";
							}

							echo "</optgroup>";
						}
					?>
					
				</select>

			</div>
		</div>

		<h3>Experiment Variation <span class="ab-press-pro-label ">Pro Feature</span></h3>

		<p>Upgrade to AB Press Optimize Pro and get unlimited variations and the ability to test images and HTML content blocks. <a href="http://abpressoptimizer.com?utm_source=abp_lite&utm_medium=upgrade_link _button&utm_campaign=pro_upgrade" target="_blank"><strong>Upgrade Now</strong></a></p>


		<div class="variationContainer">
			<div class="variationItem">
 				<input type="hidden" name="vId[]" value="<?php echo isset($experiment->variations[0]->id) ? $experiment->variations[0]->id : "" ;?>" >

				<label class="ab-press-variation-label-name" for="variationName[]">Name</label>
				<input type="text" name="variationName[]" class="ab-press-variation-name variationName" value="<?php echo isset($experiment->variations[0]->name) ? $experiment->variations[0]->name : ""; ?>">
				<label class="ab-press-variation-label" for="variation[]">Content</label>
				<input type="text" name="variation[]" class="ab-press-variation variation" value="<?php echo isset($experiment->variations[0]->value) ? $experiment->variations[0]->value : "" ; ?>">
				<label class="ab-press-class-label" for="class[]">Element Class</label>
				<input type="text" name="class[]" class="ab-press-class" value="<?php echo isset($experiment->variations[0]->class)? $experiment->variations[0]->class : ""; ?>">
				
			</div>
		</div>


		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="Update Experiment">
		</p>


	</form>

	<!-- TODO: Provide markup for your options page here. -->
</div>