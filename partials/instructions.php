
	<div class="awflclvr-instructions">
<h1 id="menu">CSS Date Time Pro</h1>
<ul class="less-spacing">
 	<li><a href="#quick-start">Quick Start</a></li>
 	<li><a href="#overview">CSS Body Class Overview</a></li>
 	<li><a href="#default-classes">Default CSS Classes</a></li>
 	<li><a href="#custom-classes">Custom CSS Classes</a></li>
 	<li><a href="#settings">Settings</a></li>
</ul>
CSS Date Time Pro automatically adds date and time-related CSS styles to your <a href="#overview">HTML body element</a>. Now you can easily customize the look and feel of your website based on the month, day of the week, time of day, holidays and more. You can even add your own date and time-based events.

<strong>Want to change the background of your site in the morning? No problem! </strong>

<pre>body.morning {
     background: rgb('244,200,146);
}</pre>

<strong>Need all your content to be upside-down on April Fools Day? Super easy! </strong>

<pre>body.april-fools-day {
     transform: rotate(180deg);
}</pre>

With CSS Date Time Pro the only limit is your imagination!

<small><a href="#menu">Menu</a></small>
<hr />

<h2 id="quick-start">Quick Start</h2>
<ol class="less-spacing">
 	<li>Download and <a href="https://awfulclever.com/docs/how-to-install-wordpress-plugins/">install the plugin</a></li>
 	<li>Update the <a href="#settings">Settings</a></li>
 	<li>Add your custom CSS properties for the CSS Date Time classes</li>
</ol>

<small><a href="#menu">Menu</a></small>
<hr />

<h2 id="overview">CSS Body Class Overview</h2>
CSS, or Cascading Style Sheets, is a powerful tool for controlling the visual styling of your website. By default, WordPress includes a number of CSS classes in the HTML &lt;body&gt; element such as "home", "page-#", "logged-in". These classes can be used to your pages based on page-specific parameters.

A simple example would be using the "logged-in" class to change the background color of your &lt;header&gt; element when a user is logged in to your site. In your CSS stylesheet you could add the following code:
<pre>body.logged-in header {
     background-color: rgb(65,147,238);
}</pre>
Since the "logged-in" class is only included in the &lt;body&gt; when a user is logged in, this style would only be visible to logged in users.

Learn more about <a href="https://thethemefoundry.com/blog/how-to-customize-a-wordpress-theme/" rel="noopener" target="_blank">customizing WordPress with CSS</a>.

<small><a href="#menu">Menu</a></small>
<hr />

<h2 id="default-classes">Default CSS Classes</h2>
Full list of default CSS classes available within CSS Date Time & CSS Date Time Pro.
<table>
	<thead>
		<tr>
			<th>CSS Class Name</th>
			<th>Description</th>
			<th>Date / Time Active</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>cssdt-####</td>
			<td>Year 4-digit – <em>cssdt-2017</em></td>
			<td>Always Active</td>
		</tr>
		<tr>
			<td>cssdt-leap-year</td>
			<td>Leap Year</td>
			<td>During Leap Year</td>
		</tr>
		<tr>
			<td>cssdt-month</td>
			<td>Month name – <em>cssdt-november</em></td>
			<td>Always Active</td>
		</tr>
		<tr>
			<td>cssdt-month-##</td>
			<td>Month name and date – <em>cssdt-may-4</em></td>
			<td>Always Active</td>
		</tr>
		<tr>
			<td>cssdt-day</td>
			<td>Day name – <em>cssdt-friday</em></td>
			<td>Always Active</td>
		</tr>
		<tr>
			<td>cssdt-hour-####</td>
			<td>Hour in 24-hour format – <em>cssdt-hour-0800</em></td>
			<td>Always Active</td>
		</tr>
		<tr>
			<td>cssdt-night</td>
			<td>Night</td>
			<td>00:00:00 - 05:59:59</td>
		</tr>
		<tr>
			<td>cssdt-morning</td>
			<td>Morning</td>
			<td>06:00:00 - 11:59:59</td>
		</tr>
		<tr>
			<td>cssdt-afternoon</td>
			<td>Afternoon</td>
			<td>12:00:00 - 17:59:59</td>
		</tr>
		<tr>
			<td>cssdt-evening</td>
			<td>Evening</td>
			<td>18:00:00 - 23:59:59</td>
		</tr>
		<tr>
			<td>cssdt-am</td>
			<td>Time of day – <em>cssdt-pm</em></td>
			<td>Always Active</td>
		</tr>
		<tr>
			<td>cssdt-new-years-day</td>
			<td>New Years Day</td>
			<td>01-01</td>
		</tr>
		<tr>
			<td>cssdt-mlk-day</td>
			<td>MLK Day</td>
			<td>Variable Monday 01-15 - 01-21</td>
		</tr>
		<tr>
			<td>cssdt-valentines-day</td>
			<td>Valentines Day</td>
			<td>02-14</td>
		</tr>
		<tr>
			<td>cssdt-washingtons-birthday</td>
			<td>Washingtons Birthday</td>
			<td>Variable Monday 02-15 - 2-21</td>
		</tr>
		<tr>
			<td>cssdt-st-patricks-day</td>
			<td>St Patricks Day</td>
			<td>03-17</td>
		</tr>
		<tr>
			<td>cssdt-april-fools-day</td>
			<td>April Fools Day</td>
			<td>04-01</td>
		</tr>
		<tr>
			<td>cssdt-mothers-day</td>
			<td>Mothers Day</td>
			<td>Variable Sunday 05-08 - 05-15</td>
		</tr>
		<tr>
			<td>cssdt-memorial-day</td>
			<td>Memorial Day</td>
			<td>Variable Monday 05-25 - 05-31</td>
		</tr>
		<tr>
			<td>cssdt-fathers-day</td>
			<td>Fathers Day</td>
			<td>Variable Sunday 06-15 - 06-21</td>
		</tr>
		<tr>
			<td>cssdt-labor-day</td>
			<td>Labor Day</td>
			<td>Variable Monday 09-01 - 09-07</td>
		</tr>
		<tr>
			<td>cssdt-columbus-day</td>
			<td>Columbus Day</td>
			<td>Variable Monday 10-08 - 10-14</td>
		</tr>
		<tr>
			<td>cssdt-halloween</td>
			<td>Halloween</td>
			<td>10-31</td>
		</tr>
		<tr>
			<td>cssdt-thanksgiving-day</td>
			<td>Thanksgiving Day</td>
			<td>Variable Thursday 11-22 - 11-28</td>
		</tr>
		<tr>
			<td>cssdt-veterans-day</td>
			<td>Veterans Day</td>
			<td>11-11</td>
		</tr>
		<tr>
			<td>cssdt-christmas-eve</td>
			<td>Christmas Eve</td>
			<td>12-24</td>
		</tr>
		<tr>
			<td>cssdt-christmas-day</td>
			<td>Christmas Day</td>
			<td>12-25</td>
		</tr>
		<tr>
			<td>cssdt-new-years-eve</td>
			<td>New Years Eve</td>
			<td>12-31</td>
		</tr>
		<tr>
			<td>cssdt-new-years-day</td>
			<td>New Years Day</td>
			<td>01-01</td>
		</tr>
	</tbody>
</table>

<small><a href="#menu">Menu</a></small>
<hr />

<h2 id="custom-classes">Custom CSS Classes</h2>
Adding custom CSS classes for one-time and recurring events is easy with CSS Date Time Pro.

Custom CSS classes are available only in <a href="https://awfulclever.com/downloads/css-date-time-pro/?ref=plugin-lite" target="_blank">CSS Date Time Pro</a>

<h3>Recurring Full-day Events</h3>
For recurring full-day events, enter the month and date followed by the CSS class name in the Recurring Full-day Events text area. 

The following event would add the class "tax-day" every April 15th.

<pre>
04-15 tax-day
</pre>

Classes should be lowercase and not contain any spaces. Enter one event per line. 

<h3>One-Time Full-day Events</h3>
For one-time full-day events, enter the month, date and year followed by the CSS class name in the One-time Full-day Events text area. 

The following event would add the class "super-bowl-sunday" on February 2nd, 2018 and "academy-awards" on March 3rd, 2018.

<pre>
2018-02-04 super-bowl-sunday 
2018-03-04 academy-awards 
</pre>

Classes should be lowercase and not contain any spaces. Enter one event per line. 

<small><a href="#menu">Menu</a></small>
<hr />

<h2 id="settings">Settings</h2>

<h3>Enable Javascript Callback</h3>
Appends CSS classes after the page loads via Javascript to bypass page caching. Classes are added post-load there will be a brief delay before the classes take effect.

<h4>What is Caching?</h4>
WordPress caching plugins speed up page load times by reducing server load. Caching saves the entire page including the CSS body classes. This can pose a problem with date and time-based content as the content is not refreshed when each user visits the page.

If your caching plugin or service is set to cache pages for an hour or less, this may not pose much of an issue. At most, your dates and time will be off by no more than one hour.

If you are caching pages for more time, or if you would like the CSS classes immediately available, check the box and "Enable Javascript Callback".

<h3>CSS Prefix</h3>
Replace the default "cssdt-" prefix with your own prefix.

<h3>One-time Full-day Events</h3>
Add one-time full-day events. <a href="https://awfulclever.com/downloads/css-date-time-pro/?ref=plugin-lite" target="_blank">Pro version only</a>.

<h3>Recurring Full-day Events</h3>
Add recurring full-day events. <a href="https://awfulclever.com/downloads/css-date-time-pro/?ref=plugin-lite" target="_blank">Pro version only</a>.

<small><a href="#menu">Menu</a></small>

	</div>
	
	