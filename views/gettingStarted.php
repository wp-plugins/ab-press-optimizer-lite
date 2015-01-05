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
		<a href="admin.php?page=abpo-experiment" class="nav-tab">Experiments</a>
		<a href="" class="nav-tab  nav-tab-active">Getting Started</a>
	</h2>
    <div id="contextual-help-columns">
    <div class="contextual-help-tabs">
        <ul>
            <li id="tab-link-quick_start" class="active">
                <a href="#tab-panel-quick_start" aria-controls="tab-panel-quick_start"> Quick Start</a>
            </li>
            <li id="tab-link-glossary" class="">
                <a href="#tab-panel-glossary" aria-controls="tab-panel-glossary"> Glossary</a>
            </li>
            <li id="tab-link-what_is_ab_testing" class="">
                <a href="#tab-panel-what_is_ab_testing" aria-controls="tab-panel-what_is_ab_testing"> What is A/B Testing?</a>
            </li>
            <li id="tab-link-new_experiment_help" class="">
                <a href="#tab-panel-new_experiment_help" aria-controls="tab-panel-new_experiment_help">Understanding Experiments</a>
            </li>
            <li id="tab-link-licencing" class="">
                <a href="#tab-panel-licencing" aria-controls="tab-panel-licencing">Plugin Licencing</a>
            </li>
            <li id="tab-link-misc" class="">
                <a href="#tab-panel-misc" aria-controls="tab-panel-misc"> Misc.</a>
            </li>
        </ul>
    </div>
    <div class="contextual-help-tabs-wrap">
        <div id="tab-panel-quick_start" class="help-tab-content active" style="">
            <h2>Quick Start Guide</h2>
            <ol>
                <li>Click "Add new"</li>
                <li>Enter an experiment name</li>
                <li>Select a start and end date</li>
                <li>Enter the goal of this experiment</li>
                <li>Select an experiment type (goal trigger)</li>
                <li>If you've selected an experiment type other than "Click Event" select the page you want to trigger a conversion. (Please make sure that the page you select is the next chronological page to where you will be conducting your experiment )</li>
                <li>Click "Add Variation"</li>
                <li>Once you have created all the variations you wanted click "Save Experiment"</li>
                <li>To embed the experiment go to the page, post or custom post type and add the Ab Press Optimizer Shortcode</li>
            </ol>
            <h2>Embedding Experiments</h2>
            <h3>Video example</h3>
            <a href='https://www.youtube.com/watch?v=VczhD5xiLG0'>AB Press Optimizer - Getting Started</a>
            <h3>Shortcode</h3>
            <p>To embed your experiment into any post, page or custom post type simply wrap the element you are testing (i.e. the control) with the Shortcode and pass it the experiment Id. If you would like to run an experiment across multiple pages simply add the multisite attribute ex:( multipage=True).</p>
            <p>EX: </p>
            <pre>[abPress id="1"] &#60;a href=""&#62;Link&#60;/a&#62; [/abPress] </pre>
            <p>You can also use the AB Press Optimizer button in the editor toolbox which allows you to select the experiment and will insert the Shortcode into the page or post. </p>
            <img src="<?php echo plugins_url('/assets/editorbutton.png', dirname(__FILE__)); ?>"/>
        </div>
        <div id="tab-panel-glossary" class="help-tab-content" style="">
            <h2>Experiment Types (Goal Triggers)</h2>
            <h3>Page View</h3>
            <p>A page view experiment is for tracking when a user/customer visits a page for the first time. This is perfect for testing images, taglines and calls to action.
                This test can help you determine what elements on your page can get users further into your website for example your pricing page or features page.
                The page view type will trigger a conversion when the user visits the page you've selected as the target URL for this experiment for the first time.</p>
            <h3>Click Event <span class="ab-press-pro-label ">Pro Feature</span></h3>
            <p>A Click Event experiment is for tracking when a user/customer clicks on an element for the first time. This is perfect for experimenting with button styles and calls to actions.
                This test can help you determine how to get users/customers to better interact with elements on your page.
                There is also a "Click Event Ajax" trigger which differs slightly from the "Click Event". The "Click Event" will trigger a conversion when the element being tested is clicked upon AND
                this results in a page load. The "Click Event Ajax" will trigger exactly when the element is clicked on, meaning it is very useful for tracking on page interactions like modals, accordions,
                ajax requests and other interactions which do not load/reload a page.</p>
            <h3>Submit a Form <span class="ab-press-pro-label ">Pro Feature</span></h3>
            <p>A Submit Form experiment is for tracking when a user/customer submits a form for the first time.
                Using this trigger will track which variations lead to the most form submissions and a conversion is triggered on successful submission of a form on the page containing the shortcode
                for the experiment.</p>
            <h3>Post Title <span class="ab-press-pro-label ">Pro Feature</span></h3>
            <p>A Post Title experiment is for tracking which post/page titles lead to the most post/page views.  Every time a title is seen by a user it is marked as a visit and whenever a user
                clicks on the title or visits the page, that title variation will be marked as a conversion. <br/> Post title experiments can only use Text variations and are created the same way as
                other experiments.  Simply choose the URL of the page/post you are testing the title for and it's original title will be set as the Control.</p>
            <h2>Variation Types</h2>
            <h3>Text</h3>
            <p>Replace the text in a HTML element and/or apply custom CSS classes for styling. AB Press Optimizer currently supports the following tags a, p, div, span, section, and input.</p>
            <h3>HTML <span class="ab-press-pro-label ">Pro Feature</span></h3>
            <p>This variation will replace complete section of html with the html from the variation.
                Add your html snippet and it will replace the control html when this variation shows </p>
            <h3>Image <span class="ab-press-pro-label ">Pro Feature</span></h3>
            <p>Replace or add images and/or apply custom CSS classes for styling.
                If your control is an image, then the image will be replaced but if the image does not exist we will generate the image tags for the variation.</p>
            <h2>Terms</h2>
            <h3>Visitors</h3>
            <p>Number of unique visitors who have taken part in the experiment. To count as part of the experiment, a visitor must first arrive on a page with the experiment.</p>
            <h3>Conversions</h3>
            <p>Number of unique visitors who have taken part in the experiment and the end goal for the experiment.</p>
            <h3>Conversion Rate</h3>
            <p>Percentage of visitors who reached the experiments end goal from the visitors who have taken part in the experiment.</p>
            <h3>Control</h3>
            <p>This is the control element for the experiment, meaning the current value for the text/html/image you wish to test your variations against.</p>
            <h3>Variation</h3>
            <p>A variation of the control value, the variation will randomly replace the control text/html/image shown to visitors.</p>
            <h3># Variations</h3>
            <p>Number of instances you're testing in an experiment.</p>
            <h3>ID</h3>
            <p>Unique identifier for a specific experiment.</p>
            <h3>Goal</h3>
            <p>A description of what the user needs to do in order to constitute a conversion.</p>
            <h3>Improvement</h3>
            <p>Percentage of improvement of the variation conversion rate compared to the original conversion rate. Improvement can be positive or negative.</p>
            <h3>Chance to beat original (Statistical Confidence)</h3>
            <p>Confidence level of the variation results (a value between 0%-100%) is a metric which indicates the confidence we have in a variation performing better compared to Control.
                When a Variation reaches the 95% threshold with a sample size of at least 30 visitors we declare it as a winner. </p>
            <h3>Multipage experiment</h3>
            <p>Setting the experiment Shortcode to be "multipage='True'" means that once a variation is chosen for a user, AB Press Optimizer will use the same variation across all pages where that content appears on your website.
                This is useful for testing things like menus or header banners where the same content appears on multiple pages.  Forcing multipage='True' in these cases will ensure the statistics remain consistent
                by showing the same variation throughout.</p>
        </div>
        <div id="tab-panel-what_is_ab_testing" class="help-tab-content" style="">
            <h2>What is A/B Testing?</h2>
            <p>A/B testing, also called split testing, is a simple yet highly effective way to determine which designs produce optimal results. By removing the guesswork and applying a scientific approach, A/B testing gives you quantitative data from which you can make decisions about the layout and design of your website. Visitors to your website are shown multiple versions of a page and the statistics reveal which options convert. No more “gut decisions” that may or may not be true, A/B testing allows users to literally show you what they prefer.</p>
            <p>We all know that one of the best ways to guarantee future success is to study what’s worked in the past. Using A/B testing for webpage optimization is a fast and accurate way to determine what’s working for the people visiting your site. Whether you’re hoping to increase downloads, sign-ups, purchases or user interaction, taking a measured approach and analyzing the data will reveal the clearest path to success.</p>
            <h3> What happens during A/B testing?</h3>
            <p>An A/B test compares two different features on a web page and discovers which produces the best results. Version A is called “the control” while Version B is “the variable.” A/B testing works best when you begin the experiment knowing exactly what you’re looking for. In this example, let’s imagine you’re an up-and-coming author and want to see how you can increase the amount of downloads of your latest book.</p>
            <p> On your sales page you have a green button that says, “Buy Now!” but you’re wondering if a different color might work better. Version A, or the control, stays green while in Version B you test the color blue.</p>
            <p> As each of these tests run, every visitor to your website is shown either Version A or Version B. Our software makes sure an equal amount of visitors are shown each option and measures which button has the most impact. After a predetermined amount of time, you check back to see which version is doing better. Out of 100 page views, nearly 5% clicked on the blue button and downloaded your book while only 1.3% clicked on the green.</p>
            <p> Now armed with that information, you start another split test focusing on the efficacy of your button text. Version A (in a blue button, remember!) says “Buy Now!” while Version B reads “Special Offer!” You soon discover that for best results and highest books sales, you should stick with a blue “Buy Now!” button. Your users just told you exactly how to sell to them and you’re on your way to the bestsellers list!</p>
            <p> The applications of A/B testing are nearly limitless. By zeroing in on what works, you can discard what doesn’t, and watch your site flourish.</p>
        </div>
        <div id="tab-panel-new_experiment_help" class="help-tab-content" style="">
            <h2>Understanding Experiments</h2>
            <p>Experiments are the core of AB Press Optimizer and define how you use the plugin to hit your goals.
                An experiment is a way of defining how and what you would like to A/B test on your WordPress website.</p>
            <p>An experiment consists of a name, description and goal as well as dates you wish the experiment to run.
                Finally, an experiment consists of one or more variations to use throughout the testing.</p>
            <p>At it's core, an A/B test experiment compares your current content (the control) against each of the defined
                variations and tracks exactly how your users interact with them. During your experiment you will see a detailed
                breakdown of statistics regarding things like visits, 'conversions' and percentages relating to performance of the
                variations against the control towards your goal.</p>
            <p>Once your experiment has finished, you will easily be able to see which variation performed the best and by
                how much. Using that information you can improve your website to increase user interaction leading to more sales,
                newsletter sign-ups, readers and other conversions.</p>
            <h3>Variations</h3>
            <p>Variations are alternate versions of your current content that you'd like to test against that content to see which performs better.
                A good example of this is changing the color of a call-to-action button. In this scenario, your current button (i.e. the "control") may be green but you'd like to
                test if different colors will lead more clicks of that button. For this experiment, you could use several Text or HTML variations, each of which can apply different
                CSS classes to your button, making it red, blue, yellow and so on.
                <br/>At the end of this experiment, you will clearly be able to see which variation resulted
                in the most conversions. No more guesswork, you'll know for sure that the red button is best!</p>
            <p>You can use variations to test all sorts of things, from changing the CSS classes on any HTML elements, to updating the text of your buttons, replacing entire
                sections of HTML or even testing out different images. By taking a step by step approach and picking lots of variations, you can optimize your website into an
                extremely efficient conversion tool.</p>
            <h3>Conversions</h3>
            <p>Conversions are the ultimate goal of A/B testing. Using AB Press Optimizer, you will run experiments, see detailed statistics about the best performing parts of
                your website and using those statistics make changes to increase your overall conversions.</p>
            <p>A conversion can be whatever kind of interaction you decide, from increasing clicks or page views to improving sales or newsletter sign-ups.</p>
            <h3>Statistics</h3>
            <p>During testing you are presented with various statistics indicating how the experiment is performing. Below are the types of statistics and what they mean:</p>
            <ul>
                <li><strong>Conversion Rate
                    </strong> - Percentage of visitors who reached the experiments end goal from the visitors who have taken part in the experiment.
                </li>
                <li><strong>Improvement
                    </strong> - Percentage of improvement of the variation conversion rate compared to the original conversion rate. Improvement can be positive or negative.
                </li>
                <li><strong>Chance to beat original (Statistical Confidence)
                    </strong> - Confidence level of the variation results (a value between 0%-100%) is a metric which indicates the confidence we have in a Variation performing better compared to Control.
                </li>
                <li><strong>Conversions
                    </strong> - Number of unique visitors who have taken part in the experiment and the end goal for the experiment.
                </li>
                <li><strong>Visitors
                    </strong> - Total number of unique visitors who have taken part in the experiment, including both those who have and have not reached the experiments end goal (i.e. converted).
                </li>
            </ul>
            <h4>Charts <span class="ab-press-pro-label ">Pro Feature</span></h4>
            <p>You can view detailed statistics charts for each experiment by clicking the "View Detailed Statistics" button from the Experiment details page.  Currently there are 4 types of charts (more coming in
                future!) to give you a better insight to the experiment results.  These are: </p>
            <ul>
                <li><strong>Visitors By Date</strong> - Number of visitors by date over the last 30 days.</li>
                <li><strong>Visitors By Time</strong> - How many visitors you receive based on the time of day.  This tracks total all time visitors.</li>
                <li><strong>Visitors By Browser</strong> - Number of visitors by the web browser they use. This tracks total all time visitors.</li>
                <li><strong>Visitors By Platform</strong> - Number of visitors by the operating system they use. This tracks total all time visitors.</li>
                <li><strong>Conversions By Date</strong> - Number of conversions by date over the last 30 days.</li>
                <li><strong>Conversions By Time</strong> - How many conversions you receive based on the time of day.  This tracks total all time conversions.</li>
                <li><strong>Conversions By Browser</strong> - Number of conversions by the web browser they use. This tracks total all time conversions.</li>
                <li><strong>Conversions By Platform</strong> - Number of conversions by the operating system they use. This tracks total all time conversions.</li>
            </ul>
            <p>Note: upon installation of AB Press Optimizer, not enough data will be compiled to show any of the charts but it should begin to appear after a few days.</p>
            <h3>Learning from experiment results</h3>
            <p>At the end of the experiment you will have a very clear indication of the highest converting variation.  The key statistics to look at are the <strong>Conversion Rate</strong>,
                the <strong>Improvement</strong> and the actual numbers of conversions and visitors.</p>
            <p>The conversion rate gives you the exact data on how each variation performed and the improvement shows how much better these variations perform compared to the control.
                Knowing this, you can remove the experiment shortcode and replace the control with the winning variation from the experiment (we will be adding future functionality to automate this).<br/>
                You may even decide to test different variations against the winning one by creating another experiment, thus honing your website to the most optimal conversion rate you can get.
                The best conversion rates are achieved by continually running experiments and applying the lessons derived from the results on all parts of your website.</p>
            <h2>How to embed an experiment</h2>
            <h3>Video example</h3>
            <a href='https://www.youtube.com/watch?v=VczhD5xiLG0'>AB Press Optimizer - Getting Started</a>
            <h3>Shortcode</h3>
            <p>To embed your experiment into any post, page or custom post type simply wrap the element you are testing (i.e. the control) with the Shortcode and pass it the experiment Id. If you would like to run an experiment across multiple pages simply add the multisite attribute ex:( multipage=True).</p>
            <p>EX: </p>
            <pre>[abPress id="1"] &#60;a href=""&#62;Link&#60;/a&#62; [/abPress] </pre>
            <p>You can also use the AB Press Optimizer button in the editor toolbox which allows you to select the experiment and will insert the Shortcode into the page or post. </p>
            <img src="<?php echo plugins_url('/assets/editorbutton.png', dirname(__FILE__)); ?>"/>
        </div>
        <div id="tab-panel-licencing" class="help-tab-content" style="">
            <h2>Plugin Licencing <span class="ab-press-pro-label ">Pro Feature</span></h2>
            <p>On purchase of AB Press Optimizer you will be given a licence key that entitles you to 1 year of updates and support.  After this time, the plugin will continue to work with no restrictions, however
                no future software updates or premium support can be obtained.  Software updates include updating compatibility issues if WordPress makes changes to core functionality affecting AB Press Optimizer.
                They also include all new features, improvements, bug fixes and implementation of suggestions from our customers.  We aim to continually improve AB Press Optimizer to be the best A/B testing plugin
                for WordPress.</p>
            <h3>Setting up your licence</h3>
            <p>After you have installed and activated AB Press Optimizer, you will need to enter your licence key on the Settings tab of the plugin admin page.  Once you have added the licence key (received both
                after checkout and by email) click Save and it will be stored.  You then must also click the 'Activate' button, which appears after saving your licence key, to activate your 1 year of support and updates.</p>
        </div>
        <div id="tab-panel-misc" class="help-tab-content" style="">
            <h3>PHP</h3>
            <p>If you need more flexibility and want to embed your experiment into one of your WordPress templates you can use our experiment function.
                That function takes two required parameters and one optional parameter; the first is your experiment id, the second is the element you're trying to test and the third is a Boolean for if the experiment will be ran across multiple pages. </p>
            <p>EX: </p>
            <pre> &#60;?php echo ab_press_optimizer(1, '&#60;a href=""&#62;Link&#60;/a&#62;'); ?&#62;</pre>
            <h3>Testing Conversions </h3>
            <p>In order to see your different variations you can add the URL parameter "testing" to the URL. This has to be done on the first view of your experiment.
                If you have viewed the page with your experiment and would like to see it in testing mode please delete all your browser cookies first.</p>
            <p>EX: </p>
            <pre>www.MyDomain.com/page1?testing</pre>
        </div>
    </div>
</div>