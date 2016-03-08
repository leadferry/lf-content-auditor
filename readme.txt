=== Content Auditor by LeadFerry ===
Contributors: leadferry
Author URI: https://leadferry.com/
Plugin URL: https://leadferry.com/tools/content-audit-plugin-wordpress
Tags: content marketing,content audit,content,marketing,audit,auditor
Requires at least: 3.0
Tested up to: 4.4
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Audit and create content inventory for your site. Analyze content performance and take action based on social sharing and other engagement metrics.

== Description ==

The <a href="https://leadferry.com/tools/content-audit-plugin-wordpress" target="_blank">content audit plugin</a> by LeadFerry helps your marketing team create a content inventory for your website.

The plugin adds useful information such as social sharing metrics, comment count, content reading score (based on Flesch reading ease), and several other metrics to help your team analyze content performance.

Identify high-performing content that needs updating and keep your content fresh. Act on quick-win scenarios for your users and on-page SEO.

== Installation ==

You can install the content auditor plugin by LeadFerry using several methods.

**#1: Directly from within WordPress**

Navigate to the "Add Plugin" page. Search for "Content Auditor by LeadFerry" without the quotes. Install the relevant plugin from the search result.

Alternatively, if you already have the zip file from the WordPress plugin repository, simply upload the plugin from the upload plugin page. Remember not to unzip the files.

**#2: FTP Upload**

Download the plugin file from the plugin repository. Extract the zip file and just drop the contents in the "wp-content/plugins/" directory of your WordPress installation.

Once all the files are copied, login to the WP Admin area and activate the Plugin from Plugins page.

**After Installation**

Once installed, a new menu item named "Content Auditor" will appear on your wordpress admin menu. Make sure the metrics you want in the report is checked and click on save. Once your preference has been saved, you can generate the report.

Report generation is scheduled to happen in the background. Once it's complete, you will receive an email. You can also go into the "Previously Generated Reports" tab to download previously generated reports.

== Frequently Asked Questions ==

**What are metrics supported by the audit plugin?**

*   URL
*   Page Title
*   Meta Title
*   Meta Description
*   Author
*   Publish Date
*   Last Updated
*   Total Comments
*   Number of Images
*   Number of Words
*   Flesch–Kincaid Readability Score
*   Facebook share count
*   LinkedIn share count
*   Pinterest share count

**Why don't you have Twitter/Google Plus share count?**

Our plugin relies on share count data made available to developers by the social platforms.

Twitter recently disabled providing share count. Google Plus does not have an official method to get sharing numbers yet.

Since we do not have access to the share data of these platforms, we are unable to incorporate it into our reports.

**What output formats do you support?**

Currently, we support report generation in CSV format compatible with Microsoft Excel, Open Office and other office suites.

**Why does it say it may take a long time if we select Meta Title/Meta Description?**

Meta title and meta description are often handled by third party plugins. There is no native way to pull the information from these plugins. Hence, we have to relay on a custom mechanism that takes time to ensure the data is capture and presented in your report without errors. Unchecking these metrics will result in quicker report generation.

== Screenshots ==
1. Select the metrics you want to add to your audit report and click save. Note that checking Meta Title/Meta Description can significantly impact the report generation time based on the amount of content you have on your website.

2. More options for you. Select the social sharing metrics that matters to you. Select whether to add posts or pages or both in your audit report. To ensure quick report generation and avoid report duplication, you can audit content based on their created time.

3. See and download your previously generated reports.

== Changelog ==
= 0.1.0 =
* First Release

== Upgrade Notice ==
= 0.1 =
First Release

== Credits ==
* [PHP Text Statistics](https://github.com/DaveChild/Text-Statistics) by [Dave Child](mailto:dave@addedbytes.com)
