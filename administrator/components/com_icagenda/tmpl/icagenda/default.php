<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-25
 *
 * @package     iCagenda.Admin
 * @subpackage  tmpl.icagenda
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

//use iCutilities\Theme\Theme as icagendaTheme;
use iCutilities\Categories\Categories as icagendaCategories;
use iCutilities\Update\icagendaUpdate;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

// Check Theme Packs Compatibility (to be changed to a little note button with modal)
//if (class_exists('icagendaTheme')) icagendaTheme::checkThemePacks();

// Get Application
$app = Factory::getApplication();

$user   = Factory::getUser();
$userId = $user->get('id');

$params      = ComponentHelper::getParams('com_icagenda');
$version     = $params->get('version');
$icsys       = $params->get('icsys');
$release     = $params->get('release');
$translator  = Text::_('COM_ICAGENDA_TRANSLATOR');
$iCategories = icagendaCategories::getList('1');

$db = Factory::getDbo();
$query	= $db->getQuery(true);
$query->select('version AS icv, releasedate AS icd, params AS icp')->from('#__icagenda')->where('id = 3');
$db->setQuery($query);
$result  = $db->loadObject();
//$release = $result->icv;
$date    = $result->icd;
$icp     = json_decode($result->icp, true);

if (version_compare(phpversion(), '5.3.10', '<')) {
	$JoomlaRecommended = '5.4 +';

	$icon_warning = '<span class="cpanel-icon-warning"></span>';

	$php_warning_msg = '<strong> ' . Text::sprintf('COM_ICAGENDA_YOUR_PHP_VERSION_IS', phpversion()) . '</strong><br />';
	$php_warning_msg.= Text::sprintf('COM_ICAGENDA_PHP_VERSION_JOOMLA_RECOMMENDED', $JoomlaRecommended);
	$php_warning_msg.= ' ( ' . Text::_('IC_READMORE') . ': ';
	$php_warning_msg.= '<a href="http://www.joomla.org/technical-requirements.html"';
	$php_warning_msg.= ' target="_blank">http://www.joomla.org/technical-requirements.html</a> )<br />';
	$php_warning_msg.= Text::_('COM_ICAGENDA_PHP_VERSION_ICAGENDA_RECOMMENDATION');

	$app->enqueueMessage( $icon_warning . $php_warning_msg, 'error' );
}
?>
<div id="container">
	<div class="row">
		<div class="col-12">
			<div class="row">
				<div class="col-lg-7">
				<div class="ic-panel">

					<div class="row">
						<div class="col-lg-6 text-center">

							<h3><?php echo Text::_('COM_ICAGENDA_TITLE_CATEGORIES'); ?></h3>

							<?php if ($user->authorise('icagenda.access.categories', 'com_icagenda')) : ?>
								<a href="index.php?option=com_icagenda&view=categories">
									<div class="icpanel-icon">
										<img alt="<?php echo Text::_('COM_ICAGENDA_PANEL_CATEGORY'); ?>" src="../media/com_icagenda/images/all_cats-48.png">
										<div class="icpanel-icon-text-container ic-bg-grey">
											<div class="icpanel-icon-text">
												<?php echo Text::_('COM_ICAGENDA_PANEL_CATEGORY'); ?>
											</div>
										</div>
									</div>
								</a>
							<?php else : ?>
								<div class="icpanel-icon denied">
									<img alt="<?php echo Text::_('JERROR_ALERTNOAUTHOR'); ?>" src="../media/com_icagenda/images/panel_denied/all_cats-48.png">
									<span class="icpanel-icon-text denied">
										<?php echo Text::_('COM_ICAGENDA_PANEL_CATEGORY'); ?>
									</span>
								</div>
							<?php endif; ?>

							<?php if ($user->authorise('icagenda.access.categories', 'com_icagenda') && $user->authorise('core.create', 'com_icagenda')) : ?>
								<a href="index.php?option=com_icagenda&view=icategory&layout=edit">
									<div class="icpanel-icon">
										<img alt="<?php echo Text::_('COM_ICAGENDA_PANEL_NEW_CATEGORY'); ?>" src="../media/com_icagenda/images/new_cat-48.png">
										<div class="icpanel-icon-text-container ic-bg-red">
											<div class="icpanel-icon-text">
												<?php echo Text::_('COM_ICAGENDA_PANEL_NEW_CATEGORY'); ?>
											</div>
										</div>
									</div>
								</a>
							<?php else : ?>
								<div class="icpanel-icon denied">
									<img alt="<?php echo Text::_('JERROR_ALERTNOAUTHOR'); ?>" src="../media/com_icagenda/images/panel_denied/new_cat-48.png">
									<span class="icpanel-icon-text denied">
										<?php echo Text::_('COM_ICAGENDA_PANEL_NEW_CATEGORY'); ?>
									</span>
								</div>
							<?php endif; ?>

						</div>
						<div class="col-lg-6 text-center">

							<h3><?php echo Text::_('COM_ICAGENDA_TITLE_EVENTS'); ?></h3>

							<?php if ($user->authorise('icagenda.access.events', 'com_icagenda')) : ?>
								<a href="index.php?option=com_icagenda&view=events">
									<div class="icpanel-icon">
										<img alt="<?php echo Text::_('COM_ICAGENDA_PANEL_EVENTS'); ?>" src="../media/com_icagenda/images/all_events-48.png">
										<div class="icpanel-icon-text-container ic-bg-grey">
											<div class="icpanel-icon-text">
												<?php echo Text::_('COM_ICAGENDA_PANEL_EVENTS'); ?>
											</div>
										</div>
									</div>
								</a>
							<?php else : ?>
								<div class="icpanel-icon denied">
									<img alt="<?php echo Text::_('JERROR_ALERTNOAUTHOR'); ?>" src="../media/com_icagenda/images/panel_denied/all_events-48.png">
									<span class="icpanel-icon-text denied">
										<?php echo Text::_('COM_ICAGENDA_PANEL_EVENTS'); ?>
									</span>
								</div>
							<?php endif; ?>

							<?php if ($user->authorise('icagenda.access.events', 'com_icagenda') && $user->authorise('core.create', 'com_icagenda') && $iCategories) : ?>
								<a href="index.php?option=com_icagenda&view=event&layout=edit">
									<div class="icpanel-icon">
										<img alt="<?php echo Text::_('COM_ICAGENDA_PANEL_NEW_EVENT'); ?>" src="../media/com_icagenda/images/new_event-48.png">
										<div class="icpanel-icon-text-container ic-bg-red">
											<div class="icpanel-icon-text">
												<?php echo Text::_('COM_ICAGENDA_PANEL_NEW_EVENT'); ?>
											</div>
										</div>
									</div>
								</a>
							<?php else : ?>
								<div class="icpanel-icon denied">
									<img alt="<?php echo Text::_('JERROR_ALERTNOAUTHOR'); ?>" src="../media/com_icagenda/images/panel_denied/new_event-48.png">
									<span class="icpanel-icon-text denied">
										<?php echo Text::_('COM_ICAGENDA_PANEL_NEW_EVENT'); ?>
									</span>
								</div>
							<?php endif; ?>

						</div>
					</div>




					<div class="row">
						<div class="col-lg-6 text-center">

							<h3><?php echo Text::_('COM_ICAGENDA_TITLE_REGISTRATION'); ?></h3>

							<?php if ($user->authorise('icagenda.access.registrations', 'com_icagenda')) : ?>
								<a href="index.php?option=com_icagenda&view=registrations">
									<div class="icpanel-icon">
										<img alt="<?php echo Text::_('COM_ICAGENDA_PANEL_REGISTRATION'); ?>" src="../media/com_icagenda/images/registration-48.png">
										<div class="icpanel-icon-text-container ic-bg-grey">
											<div class="icpanel-icon-text">
												<?php echo Text::_('COM_ICAGENDA_PANEL_REGISTRATION'); ?>
											</div>
										</div>
									</div>
								</a>
							<?php else : ?>
								<div class="icpanel-icon denied">
									<img alt="<?php echo Text::_('JERROR_ALERTNOAUTHOR'); ?>" src="../media/com_icagenda/images/panel_denied/registration-48.png">
									<span class="icpanel-icon-text denied">
										<?php echo Text::_('COM_ICAGENDA_PANEL_REGISTRATION'); ?>
									</span>
								</div>
							<?php endif; ?>

							<?php if ($user->authorise('icagenda.access.newsletter', 'com_icagenda') && $user->authorise('core.create', 'com_icagenda')) : ?>
								<a href="index.php?option=com_icagenda&view=mail&layout=edit">
									<div class="icpanel-icon">
										<img alt="<?php echo Text::_('COM_ICAGENDA_PANEL_NEWSLETTER'); ?>" src="../media/com_icagenda/images/newsletter-48.png">
										<div class="icpanel-icon-text-container ic-bg-grey">
											<div class="icpanel-icon-text">
												<?php echo Text::_('COM_ICAGENDA_PANEL_NEWSLETTER'); ?>
											</div>
										</div>
									</div>
								</a>
							<?php else : ?>
								<div class="icpanel-icon denied">
									<img alt="<?php echo Text::_('JERROR_ALERTNOAUTHOR'); ?>" src="../media/com_icagenda/images/panel_denied/newsletter-48.png">
									<span class="icpanel-icon-text denied">
										<?php echo Text::_('COM_ICAGENDA_PANEL_NEWSLETTER'); ?>
									</span>
								</div>
							<?php endif; ?>

						</div>
						<div class="col-lg-6 text-center">

							<h3><?php echo Text::_('COM_ICAGENDA_ADDITIONALS_LABEL'); ?></h3>

							<?php if ($user->authorise('icagenda.access.customfields', 'com_icagenda')) : ?>
								<a href="index.php?option=com_icagenda&view=customfields">
									<div class="icpanel-icon">
										<img alt="<?php echo Text::_('COM_ICAGENDA_PANEL_CUSTOMFIELDS'); ?>" src="../media/com_icagenda/images/customfields-48.png">
										<div class="icpanel-icon-text-container ic-bg-grey">
											<div class="icpanel-icon-text">
												<?php echo Text::_('COM_ICAGENDA_PANEL_CUSTOMFIELDS'); ?>
											</div>
										</div>
									</div>
								</a>
							<?php else : ?>
								<div class="icpanel-icon denied">
									<img alt="<?php echo Text::_('JERROR_ALERTNOAUTHOR'); ?>" src="../media/com_icagenda/images/panel_denied/customfields-48.png">
									<span class="icpanel-icon-text denied">
										<?php echo Text::_('COM_ICAGENDA_PANEL_CUSTOMFIELDS'); ?>
									</span>
								</div>
							<?php endif; ?>

							<?php if ($user->authorise('icagenda.access.features', 'com_icagenda') && $user->authorise('core.create', 'com_icagenda')) : ?>
								<a href="index.php?option=com_icagenda&view=features">
									<div class="icpanel-icon">
										<img alt="<?php echo Text::_('COM_ICAGENDA_PANEL_FEATURES'); ?>" src="../media/com_icagenda/images/features-48.png">
										<div class="icpanel-icon-text-container ic-bg-grey">
											<div class="icpanel-icon-text">
												<?php echo Text::_('COM_ICAGENDA_PANEL_FEATURES'); ?>
											</div>
										</div>
									</div>
								</a>
							<?php else : ?>
								<div class="icpanel-icon denied">
									<img alt="<?php echo Text::_('JERROR_ALERTNOAUTHOR'); ?>" src="../media/com_icagenda/images/panel_denied/features-48.png">
									<span class="icpanel-icon-text denied">
										<?php echo Text::_('COM_ICAGENDA_PANEL_FEATURES'); ?>
									</span>
								</div>
							<?php endif; ?>

						</div>
					</div>

				</div>
				<div class="ic-panel">

					<div class="row">
						<div class="col-lg-6 text-center">

							<h3><?php echo Text::_('COM_ICAGENDA_GLOBAL_PARAMS_LABEL'); ?></h3>

							<?php if ($user->authorise('core.admin', 'com_icagenda')) : ?>
								<a href="index.php?option=com_config&view=component&component=com_icagenda&path=&return=<?php echo base64_encode(Uri::getInstance()->toString()) ?>">
									<div class="icpanel-icon">
										<img alt="<?php echo Text::_('JTOOLBAR_OPTIONS'); ?>" src="../media/com_icagenda/images/global_options-48.png">
										<div class="icpanel-icon-text-container ic-bg-grey-light">
											<div class="icpanel-icon-text">
												<?php echo Text::_('JTOOLBAR_OPTIONS'); ?>
											</div>
										</div>
									</div>
								</a>
							<?php else : ?>
								<div class="icpanel-icon denied">
									<img alt="<?php echo Text::_('JERROR_ALERTNOAUTHOR'); ?>" src="../media/com_icagenda/images/panel_denied/global_options-48.png">
									<span class="icpanel-icon-text denied">
										<?php echo Text::_('JTOOLBAR_OPTIONS'); ?>
									</span>
								</div>
							<?php endif; ?>

							<?php if ($user->authorise('icagenda.access.themes', 'com_icagenda') && $user->authorise('core.create', 'com_icagenda')) : ?>
								<a href="index.php?option=com_icagenda&view=themes">
									<div class="icpanel-icon">
										<img alt="<?php echo Text::_('COM_ICAGENDA_PANEL_THEMES'); ?>" src="../media/com_icagenda/images/themes-48.png">
										<div class="icpanel-icon-text-container ic-bg-grey-light">
											<div class="icpanel-icon-text">
												<?php echo Text::_('COM_ICAGENDA_PANEL_THEMES'); ?>
											</div>
										</div>
									</div>
								</a>
							<?php else : ?>
								<div class="icpanel-icon denied">
									<img alt="<?php echo Text::_('JERROR_ALERTNOAUTHOR'); ?>" src="../media/com_icagenda/images/panel_denied/themes-48.png">
									<span class="icpanel-icon-text denied">
										<?php echo Text::_('COM_ICAGENDA_PANEL_THEMES'); ?>
									</span>
								</div>
							<?php endif; ?>

						</div>
						<div class="col-lg-6 text-center">

							<h3><?php echo Text::_('COM_ICAGENDA_PANEL_UPDATE_AND_INFOS'); ?></h3>

							<a href="index.php?option=com_icagenda&view=info">
								<div class="icpanel-icon">
									<img alt="<?php echo Text::_('COM_ICAGENDA_INFO'); ?>" src="../media/com_icagenda/images/info-48.png">
									<div class="icpanel-icon-text-container ic-bg-grey-light">
										<div class="icpanel-icon-text">
											<?php echo Text::_('COM_ICAGENDA_INFO'); ?>
										</div>
									</div>
								</div>
							</a>

							<?php
							$icon_update = icagendaUpdate::checkUpdate();
							// Instantiate a new LayoutHelper instance and render the layout
//							$layout = new LayoutHelper('icagenda.updater.liveupdate');
							?>
							<div id="iCagendaLiveupdate" class="icpanel-icon">
								<?php //echo $layout->render($icon_update); ?>
								<?php echo LayoutHelper::render('icagenda.updater.liveupdate', $icon_update); ?>
							</div>

						</div>
					</div>



				</div>

					<?php if ($icsys == 'core' || ! $icsys) : ?>
						<div class="row">
							<div class="col-12">
								<div class="alert alert-block alert-info">
									<p>
										<h2><?php echo Text::sprintf('COM_ICAGENDA_FREE_VERSION', 'iCagenda&trade;') ?></h2>
									</p>
									<p>
										<?php echo Text::_('COM_ICAGENDA_FREE_WANT_MORE') ?>
									</p>
									<p>
										<strong><?php echo Text::_('COM_ICAGENDA_FREE_SUBSCRIBE_PRO') ?></strong>
									</p>
									<p class="text-start">
										<a href="https://www.joomlic.com/extensions/icagenda" class="btn btn-danger" title="<?php echo Text::_('COM_ICAGENDA_FREE_SUBSCRIBE_NOW'); ?>" role="button">
											<span class="icon-chevron-right"></span>&nbsp;&nbsp;<?php echo Text::_('COM_ICAGENDA_FREE_SUBSCRIBE_NOW'); ?>
										</a>
									</p>
									<p>
										<?php echo Text::_('COM_ICAGENDA_PANEL_PRO_VERSION') ?>:
										<ul>
											<li><?php echo Text::_('COM_ICAGENDA_PRO_PREMIUM_SUPPORT_LABEL') ?></li>
											<li><?php echo Text::_('COM_ICAGENDA_PANEL_PRO_MODULE_IC_EVENT_LIST') ?></li>
											<li><?php echo Text::_('COM_ICAGENDA_PANEL_PRO_PLUGIN_IC_PAYPAL') ?></li>
											<li><?php echo Text::_('COM_ICAGENDA_PANEL_PRO_FRONTEND_EDITION') ?></li>
											<li><?php echo Text::_('COM_ICAGENDA_PANEL_PRO_ITEM_VERSIONING') ?></li>
										</ul>
									</p>
								</div>
							</div>
						</div>
					<?php endif; ?>
				</div>

				<div class="col-lg-5">
					<?php echo LayoutHelper::render('icagenda.admin.logo', array('version' => $version)); ?>
					<div>

						<div class="col-12 ic-panel">
							<div>
								iCagenda&nbsp;<?php echo $icsys; ?>&nbsp;<strong><?php echo $release; ?></strong>&nbsp;<small>(<?php echo $date ;?>)</small>
							</div>
							<br />
							<a data-bs-target="#changelog" title="<?php echo Text::_('COM_ICAGENDA_PANEL_UPDATE_LOGS'); ?>" data-bs-toggle="modal">
								<button type="button" class="btn btn-info"><?php echo Text::_('COM_ICAGENDA_PANEL_UPDATE_LOGS'); ?></button>
							</a>
							<?php echo HTMLHelper::_(
								'bootstrap.renderModal',
								'changelog',
								array(
									'title'       => Text::_('COM_ICAGENDA_PANEL_UPDATE_LOGS'),
									'url'         => Route::_('index.php?option=com_icagenda&view=icagenda&layout=default_modal_changelog&tmpl=component'),
									'height'      => '500px',
									'width'       => '600px',
									'bodyHeight'  => '70',
									'modalWidth'  => '60',
									'footer'      => '<button type="button" class="btn" data-bs-dismiss="modal">'
											. Text::_('JTOOLBAR_CLOSE') . '</button>',
								)
							);
							?>

							<?php
								$addthis_removal = $params->get('addthis_removal', '');
								$addthis_termination =  $app->input->get('addthis_termination', '');

								// Get Current URL
								$thisURL = Uri::getInstance()->toString();

								$return_cp = 'index.php?option=com_icagenda';

								if ($addthis_termination == -1) {
									$this->saveDefault($addthis_termination, 'msg_addthis', '-1');
									$app->getMessageQueue(true);
									$app->enqueueMessage(Text::_('COM_ICAGENDA_WELCOME_HIDE_SUCCESS'), 'message');
									$app->redirect($return_cp);
								} elseif ($addthis_termination == 1) {
									$this->saveDefault($addthis_termination, 'msg_addthis', '1');
									$app->getMessageQueue(true);
									$app->redirect($return_cp);
								}

								$msg_addthis = isset($icp['msg_addthis']) ? $icp['msg_addthis'] : 1;
							?>
							<?php if ($msg_addthis == 1 && !$addthis_removal) : ?>
								<?php
								$app->enqueueMessage('<h2>' . Text::_('COM_ICAGENDA_MSG_ADDTHIS_TERMINATION_TITLE') . '</h2>'
									. '<p>' . Text::sprintf('COM_ICAGENDA_MSG_ADDTHIS_TERMINATION_STATEMENT', '<a href="https://www.addthis.com/" target="_blank" rel="noopener">' . Text::_('IC_READMORE') . '</a>') . '</p>'
									. '<p>' . Text::_('COM_ICAGENDA_MSG_ADDTHIS_TERMINATION_RESULT') . '</p>'
									. '<p>' . Text::sprintf('COM_ICAGENDA_MSG_ADDTHIS_TERMINATION_SOLUTIONS', '<a href="https://extensions.joomla.org/category/social-web/social-share/" target="_blank" rel="noopener">JED</a>') . '</p>'
									. '<p>' . Text::_('COM_ICAGENDA_MSG_ADDTHIS_TERMINATION_SORRY') . '</p>'
									. '<div>'
									. '<a href="' . Route::_($thisURL.'&addthis_termination=-1') . '">'
									. '<div class="btn btn-info">' . Text::_('IC_HIDE_THIS_MESSAGE') . '</div>'
									. '</a>'
									. '</div>'
									, 'warning');
								?>
							<?php endif; ?>

							<?php if ($icsys == 'pro') : ?>
								<?php
								$welcome_pro =  $app->input->get('welcome', '');

								if ($welcome_pro == -1) {
									$this->saveDefault($welcome_pro, 'msg_procp', '-1');
									$app->getMessageQueue(true);
									$app->enqueueMessage(Text::_('COM_ICAGENDA_WELCOME_HIDE_SUCCESS'), 'message');
									$app->redirect($return_cp);
								} elseif ($welcome_pro == 1) {
									$this->saveDefault($welcome_pro, 'msg_procp', '1');
									$app->getMessageQueue(true);
									$app->redirect($return_cp);
								}

								$options_link = '<a href="index.php?option=com_config&view=component&component=com_icagenda&path=&return='
												.  base64_encode(Uri::getInstance()->toString()) . '#pro">'
												. Text::_('JTOOLBAR_OPTIONS') . '</a>';

								$msg_procp = isset($icp['msg_procp']) ? $icp['msg_procp'] : 1;
								?>

								<?php if ($msg_procp == -1) : ?>
									<a href="<?php echo Route::_($thisURL.'&welcome=1') ?>">
										<button type="button" class="btn btn-outline-secondary" aria-labelledby="welcome-reload"><?php echo Text::_('COM_ICAGENDA_WELCOME_RELOAD'); ?></button>
										<div id="welcome-reload" role="tooltip">
											<?php echo HTMLHelper::_('tooltipText', '', Text::_('COM_ICAGENDA_WELCOME_RELOAD_DESC'), true, false); ?>
										</div>
									</a>
								<?php else : ?>
									<?php
									$app->enqueueMessage('<h2>' . Text::sprintf('COM_ICAGENDA_PRO_WELCOME', 'iCagenda PRO') . '</h2>'
//										. '<p>' . Text::sprintf('COM_ICAGENDA_PRO_WELCOME_PRO_ACCOUNT_INFO', 'iCagenda PRO', '<a href="https://pro.joomlic.com" target="_blank">pro.joomlic.com</a>') . '</p>'
//										. '<p>' . Text::sprintf('COM_ICAGENDA_PRO_WELCOME_PRO_NOTIFICATION_EMAILS', 'info(at)joomlic.com') . '</p>'
//										. '<p>' . Text::sprintf('COM_ICAGENDA_PRO_WELCOME_PRO_NOTIFICATION_EMAILS_FIRST', 'Pro JoomliC') . '<br />'
//										. Text::_('COM_ICAGENDA_PRO_WELCOME_PRO_NOTIFICATION_EMAILS_SECOND') . '<br />'
//										. Text::_('COM_ICAGENDA_PRO_WELCOME_PRO_CHECK_YOUR_EMAIL') . '</p>'
										. '<p>' . Text::_('COM_ICAGENDA_PRO_WELCOME_THANK_YOU') . '</p>'
										. '<h3>' . Text::_('COM_ICAGENDA_PRO_WELCOME_UPDATE_INFO_LABEL') . '</h3>'
//										. '<ul>'
//										. '<li>'
//										. '<div class="fs-3 fw-normal">' . Text::sprintf('COM_ICAGENDA_PRO_WELCOME_UPDATE_INFO_HOW', '<strong>' . Text::_('COM_ICAGENDA_PRO_UPDATE_KEY_NAME') . '</strong>') . '</div>'
										. '<div>' . Text::sprintf('COM_ICAGENDA_PRO_WELCOME_UPDATE_INFO_WHERE', '<strong>' . Text::_('COM_ICAGENDA_PRO_UPDATE_KEY_NAME') . '</strong>', '<a href="https://pro.joomlic.com/pro-account/my-pro-id" rel="noopener" target="_blank">' . Text::_('COM_ICAGENDA_PRO_ACCOUNT_LABEL') . '</a>') . '</div>'
										. '<div>' . Text::sprintf('COM_ICAGENDA_PRO_WELCOME_UPDATE_INFO_HOW', Text::_('COM_ICAGENDA_PRO_UPDATE_KEY_NAME'), '<strong>' . strtoupper(Text::_('COM_ICAGENDA_PRO_OPTIONS_LABEL')) . '</strong>', $options_link) . '</div>'
//										. '</li>'
//										. '</ul>'
										. '<p><small><em>' . Text::sprintf('COM_ICAGENDA_PRO_WELCOME_UPDATE_INFO_NOTE', Text::_('COM_ICAGENDA_PRO_UPDATE_KEY_NAME')) . '</em></small></p>'
//										. '<p>' . Text::sprintf('COM_ICAGENDA_PRO_WELCOME_PRO_FIRST_LOGIN_1', '<a href="https://pro.joomlic.com" target="_blank">pro.joomlic.com</a>') . '<br />'
//										. Text::sprintf('COM_ICAGENDA_PRO_WELCOME_PRO_FIRST_LOGIN_2', '<a href="https://pro.joomlic.com" target="_blank">pro.joomlic.com</a>') . '<br />'
//										. Text::sprintf('COM_ICAGENDA_PRO_WELCOME_PRO_OPTIONS', $options_link) . '</p>'
//										. '<p>' . Text::sprintf('COM_ICAGENDA_PRO_WELCOME_PRO_ID_1', 'iCagenda PRO') . '</p>'
										. '<h3>' . Text::_('COM_ICAGENDA_PRO_PREMIUM_SUPPORT_LABEL') . '</h3>'
										. '<div>' . Text::_('COM_ICAGENDA_PRO_WELCOME_CONTACT') . '</div>'
										. '<div>' . Text::sprintf('COM_ICAGENDA_PRO_WELCOME_SUPPORT', '<a href="https://pro.joomlic.com/support" target="_blank">Pro Ticket System</a>') . '</div>'
										. '<p><small><em>' . Text::sprintf('COM_ICAGENDA_PRO_WELCOME_CONTACT_NOTE', 'JoomliC') . '</em></small></p>'
//										. '<p><small><strong>' . Text::_('COM_ICAGENDA_PRO_WELCOME_NOTE') . '</strong></small></p>'
										. '<div>'
										. '<a href="' . Route::_($thisURL.'&welcome=-1') . '">'
										. '<div class="btn btn-info">' . Text::_('IC_HIDE_THIS_MESSAGE') . '</div>'
										. '</a>'
										. '</div>'
										, 'notice');
									?>
								<?php endif; ?>
							<?php endif; ?>
						</div>

						<div class="ic-panel ic-bg-grey-light">
							<?php
							// Statistics Charts
							$document = Factory::getDocument();
							$document->addStyleDeclaration('
								#canvas-holder {
									width: 90%;
									margin: 5%;
								}
								@media(min-width:768px) {
									#canvas-holder {
										width: 40%;
										margin: 10px 5%;
									}
								}
							');

							$charts = array(
								'categoryStats' => Text::sprintf('COM_ICAGENDA_STATS_TOP_CATEGORIES_LBL', '10') .
													'<br /><small>' . Text::_('COM_ICAGENDA_STATS_BASED_ON_ALL_EVENTS_HITS') . '</small>',
								'eventStats'    => Text::sprintf('COM_ICAGENDA_STATS_TOP_EVENTS_LBL', '10') .
													'<br /><small>' . Text::_('COM_ICAGENDA_STATS_ON_EVENT_HITS') . '</small>',
							);

							$mbString = extension_loaded('mbstring');
							?>

							<div class="row">

								<?php foreach ($charts as $chart => $title) : ?>
									<div id="canvas-holder" class="col-6">
										<h3><?php echo $title; ?></h3>
										<?php if ($this->eventHitsTotal) : ?>
											<canvas id="<?php echo $chart; ?>_area" width="160" height="160"></canvas>
										<?php else : ?>
											<span><?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?></span>
										<?php endif; ?>
									</div>

									<?php
									$script = array();
									$legend = array();

									$script[]= 'var data = [';

									foreach ($this->{$chart} AS $v)
									{
										$percent = $this->eventHitsTotal ? ($v->hits / $this->eventHitsTotal) : 0;

										$script[]= '		{';
										$script[]= ($chart == 'categoryStats')
													? '			value: ' . round(( $percent * 100 ), 2) . ','
													:  '			value: ' . $v->hits . ',';
										$script[]= '			color: "' . $v->cat_color . '",';
										$script[]= '			highlight: "#777777",';

										$label_length = $mbString ? mb_strlen($v->stats_label, 'UTF-8') : strlen($v->stats_label);
										$label_cut    = $mbString ? mb_substr($v->stats_label, 0, 20, 'UTF-8') : substr($v->stats_label, 0, 20);

										$stats_label  = $label_cut;
										$stats_label .= ($label_length > 20) ? '...' : '';

										$script[]= '			label: "' . addslashes($stats_label) . '"';
										$script[]= '		},';

										$legend_data = addslashes($v->cat_title) . '@@' . $v->cat_color;

										if ( ! in_array($legend_data, $legend))
										{
											$legend[]= $legend_data;
										}
									}

									sort($legend);

									$script[]= '	];';

									$script[]= 'var options = {';
									$script[]= '	animationEasing : "easeInOutBack",';
									$script[]= '	animationSteps : 60,';
									$script[]= '	responsive : true,';
									$script[]= '	segmentStrokeColor : "#f5f5f5",';
									$script[]= ($chart == 'categoryStats')
												? '	percentageInnerCutout : 0, tooltipTemplate: "<%if (label){%><%=label%> | <%}%><%= value %>%"'
												: '	percentageInnerCutout : 50';
									$script[]= '}';

									$script[]= '	var ctx' . $chart . ' = document.getElementById("' . $chart . '_area").getContext("2d");';
									$script[]= '	var myNewChart = new Chart(ctx' . $chart . ').Doughnut(data, options);';

									if ($this->eventHitsTotal)
									{
										echo '<script>' . implode("\n", $script) . '</script>';
									}
									?>
								<?php endforeach; ?>


							</div>
							<?php // Stats Legend ?>
							<hr />
							<div class="row">
								<div class="col-12">
									<?php foreach ($legend AS $v) : ?>
										<?php
										$ex_v = explode('@@', $v);
										$cat_title = $ex_v[0];
										$cat_color = $ex_v[1];
										?>
										<div style="display: inline-block; font-size: 12px; font-weight: bold; color: #555; height: 16px;">
											<div style="float: left; background: <?php echo $cat_color; ?>; width: 16px; height: 16px; border-radius: 8px;"></div>
											<div style="float: left; margin-left: 4px; margin-right: 8px;"><?php echo $cat_title; ?></div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>


						</div>

						<div class="ic-panel">
							<a href="https://www.icagenda.com/resources/translations" target="_blank" class="btn">
								<?php echo Text::_('COM_ICAGENDA_PANEL_TRANSLATION_PACKS_DONWLOAD');?>
							</a>
							<br />
							<?php if ($icsys != 'core' && $icsys) : ?>
								<a href='https://pro.joomlic.com/support' target="_blank" class="btn">
									<?php echo Text::_('COM_ICAGENDA_PANEL_PRO_TICKET_SUPPORT'); ?>
								</a>
								<br />
							<?php endif; ?>
							<a href='https://www.joomlic.com/forum/icagenda' target="_blank" class="btn">
								<?php echo Text::_('COM_ICAGENDA_PANEL_HELP_FORUM'); ?>
							</a>
						</div>

					</div>

				</div>
			</div>

		</div>
	</div>
	<?php echo LayoutHelper::render('icagenda.admin.footer'); ?>
</div>
