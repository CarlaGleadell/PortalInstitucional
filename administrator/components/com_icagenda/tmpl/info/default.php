<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.19 2023-10-18
 *
 * @package     iCagenda.Admin
 * @subpackage  tmpl.info
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       2.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use iCutilities\Theme\Theme as icagendaTheme;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

$params  = ComponentHelper::getParams('com_icagenda');
$icsys   = $params->get('icsys');
$version = $params->get('version');
$release = $params->get('release');

// Check Theme Packs Compatibility
icagendaTheme::checkThemePacks();

$translationPacks = array(
	'English (United Kingdom)'   => array('en-GB', 'en.gif', 'Cyril Rezé'),
	'Afrikaans (South Africa)'   => array('af-ZA', 'af.gif', 'Isileth'),
	'Arabic (Unitag)'            => array('ar-AA', 'ar.gif', 'haneen2013, fkinanah, Specialist'),
	'Basque (Spain)'             => array('eu-ES', 'eu_es.gif', 'Bizkaitarra'),
	'Bulgarian (Bulgaria)'       => array('bg-BG', 'bg.gif', 'bimbongr'),
	'Catalan (Spain)'            => array('ca-ES', 'ca.gif', 'Mussool, EduardAymerich, Figuerolero'),
	'Chinese (China)'            => array('zh-CN', 'zh.gif', 'Foxyman'),
	'Chinese (Taiwan)'           => array('zh-TW', 'tw.gif', 'jedi, hkce, rowdytang'),
	'Croatian (Croatia)'         => array('hr-HR', 'hr.gif', 'Davor Čolić (cdavor)'),
	'Czech (Czech Republic)'     => array('cz-CZ', 'cz.gif', 'Bongovo (bong)'),
	'Danish (Denmark)'           => array('dk-DK', 'dk.gif', 'olewolf.dk, torbenspetersen, hvitnov, dannikrstnsn, AhmadHamid, poulfrom'),
	'Dutch (Netherlands)'        => array('nl-NL', 'nl.gif', 'Molenwal1, AnneM, Walldorff, Mario Guagliardo, wfvdijk, robert.kleinpeter'),
	'English (United States)'    => array('en-US', 'us.gif', 'Cyril Rezé'),
	'Esperanto'                  => array('eo', 'eo.gif', 'Amema, Anita_Dagmarsdotter'),
	'Estonian (Estonia)'         => array('et-EE', 'et.gif', 'Reijo, Eraser'),
	'Finnish (Finland)'          => array('fi-FI', 'fi.gif', 'Kai Metsävainio (metska)'),
	'French (France)'            => array('fr-FR', 'fr.gif', 'Cyril Rezé'),
	'Galician (Spain)'           => array('gl-ES', 'gl.gif', 'XanVFR, Xnake'),
	'German (Germany)'           => array('de-DE', 'de.gif', 'grisuu, mPino, bmbsbr, Wasilis, chuerner, cordi_allemand'),
	'Greek (Greece)'             => array('el-GR', 'el.gif', 'E.Gkana-D.Kontogeorgis (elinag), kost36, rinenweb, mbini'),
	'Hungarian (Hungary)'        => array('hu-HU', 'hu.gif', 'Halilaci, magicf, Cerbo, PKH, mester93'),
	'Italian (Italy)'            => array('it-IT', 'it.gif', 'Giuseppe Bosco (giusebos)'),
	'Japanese (Japan)'           => array('ja-JP', 'ja.gif', 'nagata'),
	'Latvian (Latvia)'           => array('lv-LV', 'lv.gif', 'kredo9'),
	'Lithuanian (Lithuania)'     => array('lt-LT', 'lt.gif', 'ahxoohx'),
	'Luxembourgish (Luxembourg)' => array('lb-LU', 'icon-16-language.png', 'Superjhemp'),
	'Macedonian (Macedonia)'     => array('mk-MK', 'mk.gif', 'Strumjan (Ilija Iliev)'),
	'Norwegian Bokmål (Norway)'  => array('nb-NO', 'no.gif', 'Rikard Tømte Reitan (Rikrei)'),
	'Persian (Iran)'             => array('fa-IR', 'fa_ir.gif', 'Arash Rezvani (al3n.nvy)'),
	'Polish (Poland)'            => array('pl-PL', 'pl.gif', 'mbsrz, KISweb, gienio22, traktor, niewidzialny'),
	'Portuguese (Brazil)'        => array('pt-BR', 'pt_br.gif', 'Carosouza, alxaraujo'),
	'Portuguese (Portugal)'      => array('pt-PT', 'pt.gif', 'LFGM, macedorl, horus68'),
	'Romanian (Romania)'         => array('ro-RO', 'ro.gif', 'hat, mester93'),
	'Russian (Russia)'           => array('ru-RU', 'ru.gif', 'nshash, MSV'),
	'Serbian (latin)'            => array('sr-YU', 'sr.gif', 'Nenad Mihajlović (nenadm)'),
	'Slovak (Slovakia)'          => array('sk-SK', 'sk.gif', 'ischindl, J.Ribarszki'),
	'Slovenian (Slovenia)'       => array('sl-SI', 'sl.gif', 'erbi (Ervin Bizjak)'),
	'Spanish (Spain)'            => array('es-ES', 'es.gif', 'elerizo, mPino, albertodg, adolf64, Goncatín, claugardia, sterroso'),
	'Swedish (Sweden)'           => array('sv-SE', 'sv.gif', 'Rickard Norberg (metska), Amema, osignell, kricke'),
	'Thai (Thailand)'            => array('th-TH', 'th.gif', 'nightlight, rattanachai.ha'),
	'Turkish (Turkey)'           => array('tr-TR', 'tr.gif', 'harikalarkutusu, farukzeynep, kemalokmen'),
	'Ukrainian (Ukraine)'        => array('uk', 'uk.gif', 'Vlad Shuh (slv54)'),
);

ksort($translationPacks);
?>

<div id="container">
	<div class="row">
		<div class="col-12">
			<div class="row">
				<div class="col-lg-7">
					<div class="ic-panel">
						<div class="row">
							<h2 class="text-center">
								<?php echo Text::_('COM_ICAGENDA_PANEL_CONTRIBUTORS');?>
							</h2>
							<p class="text-center">
								<i>&ldquo; <?php echo Text::_('COM_ICAGENDA_PANEL_THANKS_TEXT'); ?> &rdquo;</i>
							</p>
							<p class="text-start">
								Ervin Bizjak, Bong, Giuseppe Bosco, Carosouza, Davor Čolić, doorknob, Reinhard Ekker, elirezo, jedi, jowe3, JonxDuo, KISweb, kredo9, macedorl, Kai Metsävainio, mussool, NicoDeluxe, Rickard Norberg, Andrzej Opejda, Régis, Tom-Henning, Rikard Tømte Reitan, Vlad Shuh, Leland Vandervort, Wilfred van Dijk, Roland van Wanrooy, David White ...
							</p>
						</div>
					</div>
					<div class="ic-panel ic-bg-grey-light">
						<div class="row">
							<h2><?php echo Text::_('COM_ICAGENDA_PANEL_TRANSLATION');?></h2>
							<div>
								<?php foreach ($translationPacks as $lang => $element) : ?>
									<?php echo '<img src="../media/mod_languages/images/' . $element[1] . '" alt="' . $element[0] . '" class="iCflag" /> &nbsp;<strong>' . $lang . ':</strong> ' . $element[2] . '<br />'; ?>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-5">
					<?php echo LayoutHelper::render('icagenda.admin.logo', array('version' => $version)); ?>
					<div class="ic-panel">
						<div class="row">
							<p>
								<strong><?php echo Text::_('COM_ICAGENDA_PANEL_LEAD_DEVELOPER');?></strong><br />
								Cyril Rezé <small>(Lyr!C)</small><!-- | <a href="https://webic.dev" target="_blank">WebiC.dev</a>-->
							</p>
							<p>
								<strong><?php echo Text::_('COM_ICAGENDA_PANEL_TEAM_1');?></strong><br />
								Giuseppe Bosco <small>(giusebos)</small>
							</p>
							<strong><?php echo Text::_('COM_ICAGENDA_PANEL_TEAM_CODE_CONTRIBUTORS');?></strong>
							<div>
								Doorknob
								<ul>
									<small>
										<li>Features</li>
										<li>Responsive Screen Threshold Widths (media css)</li>
										<li>jQuery.highlightToday.js (module calendar)</li>
									</small>
								</ul>
							</div>
							<div>
								Tom-Henning <small>(MaW)</small>
								<ul>
									<small>
										<li>iCalcreator integration (Add to iCal/Outlook)</li>
									</small>
								</ul>
							</div>
						</div>
					</div>
					<div class="ic-panel ic-bg-grey-light">
						<div class="row">
							<p>
								<strong><?php echo Text::_('COM_ICAGENDA_VERSION'); ?></strong><br />
								<?php echo $release; ?> <?php echo $version; ?>
							</p>
							<p>
								<strong><?php echo Text::_('COM_ICAGENDA_COPYRIGHT');?></strong><br />
								©&nbsp;2012-<?php echo date("Y"); ?> Cyril Rezé / <a href="https://www.icagenda.com" target="_blank">www.icagenda.com</a>
							</p>
							<p>
								<strong><?php echo Text::_('COM_ICAGENDA_LICENSE');?></strong><br />
								<a href="http://www.gnu.org/licenses/gpl.html" target="_blank">GPLv3 or later</a>
							</p>
						</div>
					</div>
					<div class="ic-panel">
						<div class="row">
							<h2><?php echo Text::_('COM_ICAGENDA_LIBRARIES');?></h2>
							<p>
								<strong>Chart.js™</strong><br />
								© Chart.js Contributors | <a href="https://www.chartjs.org/" target="_blank">www.chartjs.org</a><br />
								<small>Released under the <a href="https://github.com/chartjs/Chart.js/blob/master/LICENSE.md">MIT License</a>.</small>
							</p>
							<p>
								<strong>iCalcreator™</strong><br />
								© Kjell-Inge Gustafsson, kigkonsult, All rights reserved | <a href="https://kigkonsult.se/iCalcreator/index.php" target="_blank">kigkonsult.se/iCalcreator</a><br />
								<small>iCalcreator is licensed under the <a href="https://github.com/iCalcreator/iCalcreator/blob/master/LICENCE">LGPLv3 License</a>.</small>
							</p>
							<p>
								<strong>Google Maps™</strong><br />
								© Google Inc. | <a href="https://cloud.google.com/maps-platform/terms/" target="_blank">Google Maps Platform Terms of Service</a><br />
								<small>Google™ and Google Maps™ are registered trademarks of Google Inc.</small>
							</p>
							<p>
								<strong>OpenStreetMap<sup>®</sup></strong><br />
								© OpenStreetMap contributors | <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap Copyright and License</a><br />
								<small>OpenStreetMap® is open data, licensed under the <a href="https://opendatacommons.org/licenses/odbl/">Open Data Commons Open Database License</a> (ODbL) by the <a href="https://osmfoundation.org/">OpenStreetMap Foundation</a> (OSMF).</small>
							</p>
							<p>
								<strong>and of course... Joomla!<sup>®</sup></strong><br />
								<a href="https://www.joomla.org" target="_blank">www.joomla.org</a>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php echo LayoutHelper::render('icagenda.admin.footer'); ?>
</div>
