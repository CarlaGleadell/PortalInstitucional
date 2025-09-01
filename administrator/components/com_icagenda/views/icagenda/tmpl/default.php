<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-27
 *
 * @package     iCagenda.Admin
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

JHtml::_('behavior.modal');

// Get Application
$app    = JFactory::getApplication();
$user   = JFactory::getUser();
$userId = $user->get('id');

$params      = JComponentHelper::getParams( 'com_icagenda' );
$version     = $params->get('version');
$release     = $params->get('release');
$icsys       = $params->get('icsys');
$translator  = JText::_('COM_ICAGENDA_TRANSLATOR');
$iCategories = icagendaCategories::getList('1');
?>
<div id="j-main-container">
	<div class="row-fluid icpanel">
		<div class="span12">
			<div class="row-fluid">
				<div class="span6">
					<div class="row-fluid">
						<?php if ( $user->authorise('icagenda.access.categories', 'com_icagenda') ) : ?>
						<div class="span6" style="text-align: center">
							<table>
								<tbody>
									<tr>
										<td colspan="2">
											<h3><?php echo JText::_('COM_ICAGENDA_TITLE_CATEGORIES'); ?></h3>
										</td>
									</tr>
									<tr>
										<td>
											<div class="cpanel-icon right">
												<a href="index.php?option=com_icagenda&view=categories">
													<?php if ($user->authorise('icagenda.access.categories', 'com_icagenda')) : ?>
														<img alt=""
															src="../media/com_icagenda/images/all_cats-48.png">
														<span class="cpanel-icon-text">
															<?php echo JText::_( 'COM_ICAGENDA_PANEL_CATEGORY' ); ?>
														</span>
													<?php else : ?>
														<img alt="<?php echo JText::_( 'JERROR_ALERTNOAUTHOR' ); ?>"
															src="../media/com_icagenda/images/panel_denied/all_cats-48.png">
														<span class="cpanel-icon-text denied">
															<?php echo JText::_( 'COM_ICAGENDA_PANEL_CATEGORY' ); ?>
														</span>
													<?php endif; ?>
												</a>
											</div>
										</td>
										<td>
											<div class="cpanel-icon left">
												<a href="index.php?option=com_icagenda&view=category&layout=edit">
													<?php if ($user->authorise('icagenda.access.categories', 'com_icagenda')) : ?>
														<img alt=""
															src="../media/com_icagenda/images/new_cat-48.png">
														<span class="cpanel-icon-text">
															<?php echo JText::_( 'COM_ICAGENDA_PANEL_NEW_CATEGORY' ); ?>
														</span>
													<?php else : ?>
														<img alt="<?php echo JText::_( 'JERROR_ALERTNOAUTHOR' ); ?>"
															src="../media/com_icagenda/images/panel_denied/new_cat-48.png">
														<span class="cpanel-icon-text denied">
															<?php echo JText::_( 'COM_ICAGENDA_PANEL_NEW_CATEGORY' ); ?>
														</span>
													<?php endif; ?>
												</a>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<?php endif; ?>
						<?php if ( $user->authorise('icagenda.access.events', 'com_icagenda') ) : ?>
						<div class="span6" style="text-align: center">
							<table>
								<tbody>
									<tr>
										<td colspan="2">
											<h3><?php echo JText::_('COM_ICAGENDA_TITLE_EVENTS'); ?></h3>
										</td>
									</tr>
									<tr>
										<td>
											<div class="cpanel-icon right">
												<a href="index.php?option=com_icagenda&view=events">
													<?php if ($user->authorise('icagenda.access.events', 'com_icagenda')) : ?>
														<img alt=""
															src="../media/com_icagenda/images/all_events-48.png">
														<span class="cpanel-icon-text">
															<?php echo JText::_( 'COM_ICAGENDA_PANEL_EVENTS' ); ?>
														</span>
													<?php else : ?>
														<img alt="<?php echo JText::_( 'JERROR_ALERTNOAUTHOR' ); ?>"
															src="../media/com_icagenda/images/panel_denied/all_events-48.png">
														<span class="cpanel-icon-text denied">
															<?php echo JText::_( 'COM_ICAGENDA_PANEL_EVENTS' ); ?>
														</span>
													<?php endif; ?>
												</a>
											</div>
										</td>
										<td>
											<div class="cpanel-icon left">
												<a href="index.php?option=com_icagenda&view=event&layout=edit">
													<?php if ($user->authorise('icagenda.access.events', 'com_icagenda') && $user->authorise('core.create', 'com_icagenda') && $iCategories) : ?>
														<img alt=""
															src="../media/com_icagenda/images/new_event-48.png">
														<span class="cpanel-icon-text">
															<?php echo JText::_( 'COM_ICAGENDA_PANEL_NEW_EVENT' ); ?>
														</span>
													<?php else : ?>
														<img alt="<?php echo JText::_( 'JERROR_ALERTNOAUTHOR' ); ?>"
															src="../media/com_icagenda/images/panel_denied/new_event-48.png">
														<span class="cpanel-icon-text denied">
															<?php echo JText::_( 'COM_ICAGENDA_PANEL_NEW_EVENT' ); ?>
														</span>
													<?php endif; ?>
												</a>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<?php endif; ?>
					</div>
					<div class="row-fluid">
						<?php if ( $user->authorise('icagenda.access.registrations', 'com_icagenda')
								|| $user->authorise('icagenda.access.newsletter', 'com_icagenda') ) : ?>
						<div class="span6" style="text-align: center">
							<table>
								<tbody>
									<tr>
										<td colspan="2">
											<h3><?php echo JText::_('COM_ICAGENDA_TITLE_REGISTRATION'); ?></h3>
										</td>
									</tr>
									<tr>
										<td>
											<div class="cpanel-icon right">
												<a href="index.php?option=com_icagenda&view=registrations">
													<?php if ($user->authorise('icagenda.access.registrations', 'com_icagenda')) : ?>
														<img alt=""
															src="../media/com_icagenda/images/registration-48.png">
														<span class="cpanel-icon-text">
															<?php echo JText::_( 'COM_ICAGENDA_PANEL_REGISTRATION' ); ?>
														</span>
													<?php else : ?>
														<img alt="<?php echo JText::_( 'JERROR_ALERTNOAUTHOR' ); ?>"
															src="../media/com_icagenda/images/panel_denied/registration-48.png">
														<span class="cpanel-icon-text denied">
															<?php echo JText::_( 'COM_ICAGENDA_PANEL_REGISTRATION' ); ?>
														</span>
													<?php endif; ?>
												</a>
											</div>
										</td>
										<td>
											<div class="cpanel-icon left">
												<a href="index.php?option=com_icagenda&view=mail&layout=edit">
													<?php if ($user->authorise('icagenda.access.newsletter', 'com_icagenda')) : ?>
														<img alt=""
															src="../media/com_icagenda/images/newsletter-48.png">
														<span class="cpanel-icon-text">
															<?php echo JText::_( 'COM_ICAGENDA_PANEL_NEWSLETTER' ); ?>
														</span>
													<?php else : ?>
														<img alt="<?php echo JText::_( 'JERROR_ALERTNOAUTHOR' ); ?>"
															src="../media/com_icagenda/images/panel_denied/newsletter-48.png">
														<span class="cpanel-icon-text denied">
															<?php echo JText::_( 'COM_ICAGENDA_PANEL_NEWSLETTER' ); ?>
														</span>
													<?php endif; ?>
												</a>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<?php endif; ?>
						<?php if ( $user->authorise('icagenda.access.customfields', 'com_icagenda')
								|| $user->authorise('icagenda.access.features', 'com_icagenda') ) : ?>
						<div class="span6" style="text-align: center">
							<table>
								<tbody>
									<tr>
										<td colspan="2">
											<h3><?php echo JText::_('COM_ICAGENDA_ADDITIONALS_LABEL'); ?></h3>
										</td>
									</tr>
									<tr>
										<td>
											<div class="cpanel-icon right">
												<a href="index.php?option=com_icagenda&view=customfields">
													<?php if ($user->authorise('icagenda.access.customfields', 'com_icagenda')) : ?>
														<img alt=""
															src="../media/com_icagenda/images/customfields-48.png" />
														<span class="cpanel-icon-text">
															<?php echo JText::_( 'COM_ICAGENDA_PANEL_CUSTOMFIELDS' ); ?>
														</span>
													<?php else : ?>
														<img alt="<?php echo JText::_( 'JERROR_ALERTNOAUTHOR' ); ?>"
															src="../media/com_icagenda/images/panel_denied/customfields-48.png" />
														<span class="cpanel-icon-text denied">
															<?php echo JText::_( 'COM_ICAGENDA_PANEL_CUSTOMFIELDS' ); ?>
														</span>
													<?php endif; ?>
												</a>
											</div>
										</td>
										<td>
											<div class="cpanel-icon left">
												<a href="index.php?option=com_icagenda&view=features">
													<?php if ($user->authorise('icagenda.access.features', 'com_icagenda')) : ?>
														<img alt=""
															src="../media/com_icagenda/images/features-48.png">
														<span class="cpanel-icon-text">
															<?php echo JText::_( 'COM_ICAGENDA_PANEL_FEATURES' ); ?>
														</span>
													<?php else : ?>
														<img alt="<?php echo JText::_( 'JERROR_ALERTNOAUTHOR' ); ?>"
															src="../media/com_icagenda/images/panel_denied/features-48.png">
														<span class="cpanel-icon-text denied">
															<?php echo JText::_( 'COM_ICAGENDA_PANEL_FEATURES' ); ?>
														</span>
													<?php endif; ?>
												</a>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<?php endif; ?>
					</div>
					<div class="row-fluid">
						<?php if ( $user->authorise('core.admin', 'com_icagenda')
								|| $user->authorise('icagenda.access.themes', 'com_icagenda') ) : ?>
						<div class="span6" style="text-align: center">
							<table>
								<tbody>
									<tr>
										<td colspan="2">
											<h3><?php echo JText::_('COM_ICAGENDA_GLOBAL_PARAMS_LABEL'); ?></h3>
										</td>
									</tr>
									<tr>
										<td>
											<div class="cpanel-icon right">
												<a href="index.php?option=com_config&view=component&component=com_icagenda&path=&return=<?php echo base64_encode(JURI::getInstance()->toString()) ?>">
													<?php if ($user->authorise('core.admin', 'com_icagenda')) : ?>
														<img alt=""
															src="../media/com_icagenda/images/global_options-48.png">
														<span class="cpanel-icon-text">
															<?php echo JText::_( 'JTOOLBAR_OPTIONS' ); ?>
														</span>
													<?php else : ?>
														<img alt="<?php echo JText::_( 'JERROR_ALERTNOAUTHOR' ); ?>"
															src="../media/com_icagenda/images/panel_denied/global_options-48.png">
														<span class="cpanel-icon-text denied">
															<?php echo JText::_( 'JTOOLBAR_OPTIONS' ); ?>
														</span>
													<?php endif; ?>
												</a>
											</div>
										</td>
										<td>
											<div class="cpanel-icon left">
												<a href="index.php?option=com_icagenda&view=themes">
													<?php if ($user->authorise('icagenda.access.themes', 'com_icagenda')) : ?>
														<img alt=""
															src="../media/com_icagenda/images/themes-48.png">
														<span class="cpanel-icon-text">
															<?php echo JText::_( 'COM_ICAGENDA_PANEL_THEMES' ); ?>
														</span>
													<?php else : ?>
														<img alt="<?php echo JText::_( 'JERROR_ALERTNOAUTHOR' ); ?>"
															src="../media/com_icagenda/images/panel_denied/themes-48.png">
														<span class="cpanel-icon-text denied">
															<?php echo JText::_( 'COM_ICAGENDA_PANEL_THEMES' ); ?>
														</span>
													<?php endif; ?>
												</a>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<?php endif; ?>
						<?php if ( $user->authorise('core.admin', 'com_icagenda') ) : ?>
						<div class="span6" style="text-align: center">
							<table>
								<tbody>
									<tr>
										<td colspan="2">
											<h3><?php echo JText::_('COM_ICAGENDA_PANEL_UPDATE_AND_INFOS'); ?></h3>
										</td>
									</tr>
									<tr>
										<td>
											<div class="cpanel-icon right">
												<a href="index.php?option=com_icagenda&view=info">
													<img src="../media/com_icagenda/images/info-48.png">
													<span class="cpanel-icon-text"><?php echo JText::_( 'COM_ICAGENDA_INFO' ); ?></span>
												</a>
											</div>
										</td>
										<td class="left">
											<?php
												$icon_update = icagendaUpdate::checkUpdate();
												// Instantiate a new JLayoutFile instance and render the layout
												$layout = new JLayoutFile('icagenda.updater.liveupdate');
											?>
											<div id="iCagendaLiveupdate" class="cpanel-icon right">
												<?php echo $layout->render($icon_update); ?>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<?php endif; ?>
					</div>
					<?php if ($icsys == 'core' || ! $icsys) : ?>
					<div class="row-fluid">
						<div class="span12">
							<div class="alert alert-block alert-info">
								<p>
									<h2><?php echo JText::sprintf('COM_ICAGENDA_FREE_VERSION', 'iCagenda&trade;') ?></h2>
								</p>
								<p>
									<?php echo JText::_('COM_ICAGENDA_FREE_WANT_MORE') ?>
								</p>
								<p>
									<strong><?php echo JText::_('COM_ICAGENDA_FREE_SUBSCRIBE_PRO') ?></strong>
								</p>
								<p style="text-align: left;">
									<a href="https://www.joomlic.com/extensions/icagenda" class="btn btn-danger" title="<?php echo JText::_('COM_ICAGENDA_FREE_SUBSCRIBE_NOW'); ?>" role="button">
										<span class="icon-chevron-right"></span>&nbsp;&nbsp;<?php echo JText::_('COM_ICAGENDA_FREE_SUBSCRIBE_NOW'); ?>
									</a>
								</p>
								<p>
									<?php echo JText::_('COM_ICAGENDA_PANEL_PRO_VERSION') ?>:
									<ul>
										<li><?php echo JText::_('COM_ICAGENDA_PRO_PREMIUM_SUPPORT_LABEL') ?></li>
										<li><?php echo JText::_('COM_ICAGENDA_PANEL_PRO_MODULE_IC_EVENT_LIST') ?></li>
										<li><?php echo JText::_('COM_ICAGENDA_PANEL_PRO_PLUGIN_IC_PAYPAL') ?></li>
										<li><?php echo JText::_('COM_ICAGENDA_PANEL_PRO_FRONTEND_EDITION') ?></li>
										<li><?php echo JText::_('COM_ICAGENDA_PANEL_PRO_ITEM_VERSIONING') ?></li>
									</ul>
								</p>
							</div>
						</div>
					</div>
					<?php endif; ?>
				</div>
				<div class="span1">
				</div>
				<div class="span5">
					<div class="span12">
					<?php
						$db = JFactory::getDbo();
						$query	= $db->getQuery(true);
						//$query->select('version AS icv, releasedate AS icd')->from('#__icagenda')->where('id = 1');
						//$query->select('version AS icv, releasedate AS icd')->from('#__icagenda')->where('id = 2');
						$query->select('version AS icv, releasedate AS icd, params AS icp')->from('#__icagenda')->where('id = 3');
						$db->setQuery($query);
						$result		= $db->loadObject();
						$date		= $result->icd;
						$icp		= json_decode( $result->icp, true );

						$addthis_removal = $params->get('addthis_removal', '');
						$addthis_termination =  $app->input->get('addthis_termination', '');

						// Get Current URL
						$thisURL = JURI::getInstance()->toString();

						$return_cp = 'index.php?option=com_icagenda';

						if ($addthis_termination == -1) {
							$this->saveDefault($addthis_termination, 'msg_addthis', '-1');
							$app->getMessageQueue(true);
							$app->enqueueMessage(JText::_('COM_ICAGENDA_WELCOME_HIDE_SUCCESS'), 'message');
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
						$app->enqueueMessage('<h2>' . JText::_('COM_ICAGENDA_MSG_ADDTHIS_TERMINATION_TITLE') . '</h2>'
							. '<p>' . JText::sprintf('COM_ICAGENDA_MSG_ADDTHIS_TERMINATION_STATEMENT', '<a href="https://www.addthis.com/" target="_blank" rel="noopener">' . JText::_('IC_READMORE') . '</a>') . '</p>'
							. '<p>' . JText::_('COM_ICAGENDA_MSG_ADDTHIS_TERMINATION_RESULT') . '</p>'
							. '<p>' . JText::sprintf('COM_ICAGENDA_MSG_ADDTHIS_TERMINATION_SOLUTIONS', '<a href="https://extensions.joomla.org/category/social-web/social-share/" target="_blank" rel="noopener">JED</a>') . '</p>'
							. '<p>' . JText::_('COM_ICAGENDA_MSG_ADDTHIS_TERMINATION_SORRY') . '</p>'
							. '<div>'
							. '<a href="' . JRoute::_($thisURL.'&addthis_termination=-1') . '">'
							. '<div class="btn btn-info">' . JText::_('IC_HIDE_THIS_MESSAGE') . '</div>'
							. '</a>'
							. '</div>'
							, 'warning');
						?>
					<?php endif; ?>

					<?php
						if ($icsys == 'pro')
						{
							$welcome_pro =  $app->input->get('welcome', '');

							// Get Current URL
							$thisURL = JURI::getInstance()->toString();

							$return_cp = 'index.php?option=com_icagenda';

							if ($welcome_pro == -1)
							{
								$this->saveDefault($welcome_pro, 'msg_procp', '-1');
								$app->enqueueMessage(JText::_('COM_ICAGENDA_WELCOME_HIDE_SUCCESS'), 'message');
								$app->redirect($return_cp);
							}
							elseif ($welcome_pro == 1)
							{
								$this->saveDefault($welcome_pro, 'msg_procp', '1');
								$app->redirect($return_cp);
							}

							$options_link = '<a href="index.php?option=com_config&view=component&component=com_icagenda&path=&return='
												.  base64_encode(JURI::getInstance()->toString()) . '#pro">'
												. JText::_('JTOOLBAR_OPTIONS') . '</a>';
							?>
							<?php $msg_procp = isset($icp['msg_procp']) ? $icp['msg_procp'] : 1; ?>
							<?php if ($msg_procp == -1) : ?>
								<a class="hasTooltip" href="<?php echo JRoute::_($thisURL.'&welcome=1') ?>" data-original-title="Clear" data-toggle="tooltip" title="<?php echo JText::_('COM_ICAGENDA_WELCOME_RELOAD_DESC') ?>">
									<div class="btn btn-mini"><?php echo JText::_('COM_ICAGENDA_WELCOME_RELOAD'); ?></div>
								</a>
							<?php else : ?>
								<?php
								$app->enqueueMessage('<h2>' . JText::sprintf('COM_ICAGENDA_PRO_WELCOME', 'iCagenda PRO') . '</h2>'
									. '<p>' . JText::_('COM_ICAGENDA_PRO_WELCOME_THANK_YOU') . '</p>'
									. '<h3>' . JText::_('COM_ICAGENDA_PRO_WELCOME_UPDATE_INFO_LABEL') . '</h3>'
									. '<div>' . JText::sprintf('COM_ICAGENDA_PRO_WELCOME_UPDATE_INFO_WHERE', '<strong>' . JText::_('COM_ICAGENDA_PRO_UPDATE_KEY_NAME') . '</strong>', '<a href="https://pro.joomlic.com/pro-account/my-pro-id" rel="noopener" target="_blank">' . JText::_('COM_ICAGENDA_PRO_ACCOUNT_LABEL') . '</a>') . '</div>'
									. '<div>' . JText::sprintf('COM_ICAGENDA_PRO_WELCOME_UPDATE_INFO_HOW', JText::_('COM_ICAGENDA_PRO_UPDATE_KEY_NAME'), '<strong>' . strtoupper(JText::_('COM_ICAGENDA_PRO_OPTIONS_LABEL')) . '</strong>', $options_link) . '</div>'
									. '<p><small><em>' . JText::sprintf('COM_ICAGENDA_PRO_WELCOME_UPDATE_INFO_NOTE', JText::_('COM_ICAGENDA_PRO_UPDATE_KEY_NAME')) . '</em></small></p>'
									. '<h3>' . JText::_('COM_ICAGENDA_PRO_PREMIUM_SUPPORT_LABEL') . '</h3>'
									. '<div>' . JText::_('COM_ICAGENDA_PRO_WELCOME_CONTACT') . '</div>'
									. '<div>' . JText::sprintf('COM_ICAGENDA_PRO_WELCOME_SUPPORT', '<a href="https://pro.joomlic.com/support" target="_blank">Pro Ticket System</a>') . '</div>'
									. '<p><small><em>' . JText::sprintf('COM_ICAGENDA_PRO_WELCOME_CONTACT_NOTE', 'JoomliC') . '</em></small></p>'
									. '<div>'
									. '<a href="' . JRoute::_($thisURL.'&welcome=-1') . '">'
									. '<div class="btn btn-info">' . JText::_('IC_HIDE_THIS_MESSAGE') . '</div>'
									. '</a>'
									. '</div>'
									, 'notice');
								?>
							<?php endif; ?>
						<?php } ?>

						<div style="float:right; padding:0px 0px 0px 20px;">
							<img src="../media/com_icagenda/images/logo_icagenda.png" alt="logo_icagenda" />
						</div>
						<div>
							<h2 style="font-size:2em;">
								<strong><span style="color: #cc0000;">iC</span><span style="color: #666666;">agenda<sup style="font-size:0.5em">&trade;</sup></span></strong><small><?php echo $version;?></small>
							</h2>
						</div>
						<div>
							<h4>
								<?php echo JText::_('COM_ICAGENDA_COMPONENT_DESC') ?>
							</h4>
						</div>
						<div class="small">
							<?php echo JText::_('COM_ICAGENDA_FEATURES_BACKEND') ?><br />
							<?php echo JText::_('COM_ICAGENDA_FEATURES_FRONTEND') ?>
						</div>
						<div>&nbsp;</div>
						<div style="font-size:0.9em" class="blockbtn">
							<span style="display: none;">Joomla #__schemas release: <?php echo $result->icv; ?></span>
							<?php echo JText::_('COM_ICAGENDA_PANEL_VERSION');?>:&nbsp;<b><?php echo $release ;?></b> | <?php echo JText::_('COM_ICAGENDA_PANEL_DATE');?>:&nbsp;<b><?php echo $date ;?></b>&nbsp;&nbsp;
							<?php JHtml::_('behavior.modal'); ?>
							<div style="display:none;">
								<div id="icagenda-changelog">
									<?php
										require_once dirname(__FILE__).'/color.php';
										echo iCagendaUpdateLogsColoriser::colorise(JPATH_COMPONENT_ADMINISTRATOR.'/CHANGELOG.php');
									?>
								</div>
							</div>
							<a href="#icagenda-changelog" class="btn modal"><?php echo JText::_('COM_ICAGENDA_PANEL_UPDATE_LOGS') ?></a>
							<?php //  rel="{size: {x: 800, y: 350}}" ?>
						</div>
						<br/>
						<?php
							$urlposter = '../media/com_icagenda/images/video_poster_icagenda.jpg';
						?>
						<div>&nbsp;</div>
						<div>&nbsp;</div>
						<?php
						// Statistics Charts
						$document = JFactory::getDocument();
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
							'categoryStats' => JText::sprintf('COM_ICAGENDA_STATS_TOP_CATEGORIES_LBL', '10') .
												'<br /><small>' . JText::_('COM_ICAGENDA_STATS_BASED_ON_ALL_EVENTS_HITS') . '</small>',
							'eventStats' => JText::sprintf('COM_ICAGENDA_STATS_TOP_EVENTS_LBL', '10') .
												'<br /><small>' . JText::_('COM_ICAGENDA_STATS_ON_EVENT_HITS') . '</small>',
						);

						$mbString = extension_loaded('mbstring');
						?>

						<div class="span12" style="background: #f5f5f5; border: 1px solid #f5f5f5; border-radius: 5px 5px 0 0; margin-left: 0;">

						<?php foreach ($charts as $chart => $title) : ?>
							<div id="canvas-holder" class="span6">
								<h3><?php echo $title; ?></h3>
								<?php if ($this->eventHitsTotal) : ?>
								<canvas id="<?php echo $chart; ?>_area" width="160" height="160"></canvas>
								<?php else : ?>
								<span><?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?></span>
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

								$label_length	= $mbString ? mb_strlen($v->stats_label, 'UTF-8') : strlen($v->stats_label);
								$label_cut		= $mbString ? mb_substr($v->stats_label, 0, 20, 'UTF-8') : substr($v->stats_label, 0, 20);

								$stats_label 	= $label_cut;
								$stats_label   .= ($label_length > 20) ? '...' : '';

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
						<div class="span12" style="margin: 0; padding: 5px; border: 1px solid #f5f5f5; border-radius: 0 0 5px 5px;">
							<?php foreach ($legend AS $v) : ?>
							<?php
							$ex_v = explode('@@', $v);
							$cat_title = $ex_v[0];
							$cat_color = $ex_v[1];
							?>
							<div style="float:left; font-size: 12px; line-height: 14px; font-weight: bold; color: #555;">
								<div style="float:left; background: <?php echo $cat_color; ?>; width: 16px; height: 16px; border-radius: 8px;"></div>
								<div style="float:left; margin: 0 10px 0 5px;"><?php echo $cat_title; ?></div>
							</div>
							<?php endforeach; ?>
						</div>
						<div>&nbsp;</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row-fluid">
		<div class="span12">
			<div class="row-fluid">
				<div class="span12">
					<h3>42&nbsp;<?php echo JText::_('COM_ICAGENDA_PANEL_TRANSLATION_PACKS');?></h3>
					<p>
						<?php
							$iCtag = '<br>';
						?>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Afrikaans (South Africa)
							<?php echo $iCtag;?><?php echo $translator;?>: Isileth " >
							<img src="../media/mod_languages/images/af.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Arabic (Unitag)
							<?php echo $iCtag;?><?php echo $translator;?>: haneen2013, fkinanah, Specialist " >
							<img src="../media/mod_languages/images/ar.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Basque (Spain)
							<?php echo $iCtag;?><?php echo $translator;?>: Bizkaitarra " >
							<img src="../media/mod_languages/images/eu_es.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Bulgarian (Bulgaria)
							<?php echo $iCtag;?><?php echo $translator;?>: bimbongr " >
							<img src="../media/mod_languages/images/bg.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Catalan (Spain)
							<?php echo $iCtag;?><?php echo $translator;?>: Mussool, EduardAymerich, Figuerolero " >
							<img src="../media/mod_languages/images/ca.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Chinese (China)
							<?php echo $iCtag;?><?php echo $translator;?>: Foxyman " >
							<img src="../media/mod_languages/images/zh.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Chinese (Taiwan)
							<?php echo $iCtag;?><?php echo $translator;?>: jedi, hkce, rowdytang " >
							<img src="../media/mod_languages/images/tw.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Croatian (Croatia)
							<?php echo $iCtag;?><?php echo $translator;?>: Davor Čolić (cdavor) " >
							<img src="../media/mod_languages/images/hr.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Czech (Czech Republic)
							<?php echo $iCtag;?><?php echo $translator;?>: Bongovo (bong) " >
							<img src="../media/mod_languages/images/cz.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Danish (Denmark)
							<?php echo $iCtag;?><?php echo $translator;?>: olewolf.dk, torbenspetersen, hvitnov, dannikrstnsn, AhmadHamid, poulfrom " >
							<img src="../media/mod_languages/images/dk.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Dutch (Netherlands)
							<?php echo $iCtag;?><?php echo $translator;?>: Molenwal1, AnneM, Walldorff, Mario Guagliardo, wfvdijk, robert.kleinpeter " >
							<img src="../media/mod_languages/images/nl.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" English (United Kingdom)
							<?php echo $iCtag;?><?php echo $translator;?>: Lyr!C " >
							<img src="../media/mod_languages/images/en.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" English (United States)
							<?php echo $iCtag;?><?php echo $translator;?>: Lyr!C " >
							<img src="../media/mod_languages/images/us.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Esperanto
							<?php echo $iCtag;?><?php echo $translator;?>: Amema, Anita_Dagmarsdotter " >
							<img src="../media/mod_languages/images/eo.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Estonian (Estonia)
							<?php echo $iCtag;?><?php echo $translator;?>: Reijo, Eraser " >
							<img src="../media/mod_languages/images/et.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Finnish (Finland)
							<?php echo $iCtag;?><?php echo $translator;?>: Kai Metsävainio (metska) " >
							<img src="../media/mod_languages/images/fi.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" French (France)
							<?php echo $iCtag;?><?php echo $translator;?>: Lyr!C " >
							<img src="../media/mod_languages/images/fr.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Galician (Spain)
							<?php echo $iCtag;?><?php echo $translator;?>: XanVFR, Xnake " >
							<img src="../media/mod_languages/images/gl.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" German (Germany)
							<?php echo $iCtag;?><?php echo $translator;?>: grisuu, mPino, bmbsbr, Wasilis, chuerner, cordi_allemand " >
							<img src="../media/mod_languages/images/de.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Greek (Greece)
							<?php echo $iCtag;?><?php echo $translator;?>: E.Gkana-D.Kontogeorgis (elinag), kost36, rinenweb, mbini " >
							<img src="../media/mod_languages/images/el.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Hungarian (Hungary)
							<?php echo $iCtag;?><?php echo $translator;?>: Halilaci, magicf, Cerbo, PKH, mester93 " >
							<img src="../media/mod_languages/images/hu.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Italian (Italy)
							<?php echo $iCtag;?><?php echo $translator;?>: Giuseppe Bosco (giusebos) " >
							<img src="../media/mod_languages/images/it.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Japanese (Japan)
							<?php echo $iCtag;?><?php echo $translator;?>: nagata " >
							<img src="../media/mod_languages/images/ja.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Latvian (Latvia)
							<?php echo $iCtag;?><?php echo $translator;?>: kredo9 " >
							<img src="../media/mod_languages/images/lv.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Lithuanian (Lithuania)
							<?php echo $iCtag;?><?php echo $translator;?>: ahxoohx " >
							<img src="../media/mod_languages/images/lt.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Luxembourgish (Luxembourg)
							<?php echo $iCtag;?><?php echo $translator;?>: Superjhemp " >
							<img src="../media/mod_languages/images/icon-16-language.png" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Macedonian (Macedonia)
							<?php echo $iCtag;?><?php echo $translator;?>: Strumjan (Ilija Iliev) " >
							<img src="../media/mod_languages/images/mk.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Norwegian Bokmål (Norway)
							<?php echo $iCtag;?><?php echo $translator;?>: Rikard Tømte Reitan (rikrei) " >
							<img src="../media/mod_languages/images/no.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Persian (Iran)
							<?php echo $iCtag;?><?php echo $translator;?>: Arash Rezvani (al3n.nvy) " >
							<img src="../media/mod_languages/images/fa_ir.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Polish (Poland)
							<?php echo $iCtag;?><?php echo $translator;?>: mbsrz, KISweb, gienio22, traktor, niewidzialny " >
							<img src="../media/mod_languages/images/pl.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Portuguese (Brazil)
							<?php echo $iCtag;?><?php echo $translator;?>: Carosouza, alxaraujo " >
							<img src="../media/mod_languages/images/pt_br.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Portuguese (Portugal)
							<?php echo $iCtag;?><?php echo $translator;?>: LFGM, macedorl, horus68 " >
							<img src="../media/mod_languages/images/pt.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Romanian (Romania)
							<?php echo $iCtag;?><?php echo $translator;?>: hat, mester93 " >
							<img src="../media/mod_languages/images/ro.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Russian (Russia)
							<?php echo $iCtag;?><?php echo $translator;?>: nshash, MSV " >
							<img src="../media/mod_languages/images/ru.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Serbian (latin)
							<?php echo $iCtag;?><?php echo $translator;?>: Nenad Mihajlović (nenadm) " >
							<img src="../media/mod_languages/images/sr.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Slovak (Slovakia)
							<?php echo $iCtag;?><?php echo $translator;?>: ischindl, J.Ribarszki " >
							<img src="../media/mod_languages/images/sk.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Slovenian (Slovenia)
							<?php echo $iCtag;?><?php echo $translator;?>: erbi (Ervin Bizjak) " >
							<img src="../media/mod_languages/images/sl.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Spanish (Spain)
							<?php echo $iCtag;?><?php echo $translator;?>: elerizo, mPino, albertodg, adolf64, Goncatín, claugardia, sterroso " >
							<img src="../media/mod_languages/images/es.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Swedish (Sweden)
							<?php echo $iCtag;?><?php echo $translator;?>: Rickard Norberg (metska), Amema, osignell, kricke " >
							<img src="../media/mod_languages/images/sv.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Thai (Thailand)
							<?php echo $iCtag;?><?php echo $translator;?>: nightlight, rattanachai.ha " >
							<img src="../media/mod_languages/images/th.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Turkish (Turkey)
							<?php echo $iCtag;?><?php echo $translator;?>: harikalarkutusu, farukzeynep, kemalokmen " >
							<img src="../media/mod_languages/images/tr.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Ukrainian (Ukraine)
							<?php echo $iCtag;?><?php echo $translator;?>: Vlad Shuh (slv54) " >
							<img src="../media/mod_languages/images/uk.gif" border="0" alt="Tooltip"/>
						</span>
					</p>
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<table style="width: 100%; border: 0px;">
				<tbody>
					<tr>
						<td>
							<a href="https://www.icagenda.com/resources/translations" target="_blank" class="btn">
								<?php echo JText::_('COM_ICAGENDA_PANEL_TRANSLATION_PACKS_DONWLOAD');?>
							</a>
						</td>
						<td style="text-align:right; vertical-align: bottom;">
							<a href='https://www.joomlic.com/forum/icagenda'  target="_blank" class="btn">
								<?php echo JText::_('COM_ICAGENDA_PANEL_HELP_FORUM'); ?>
							</a>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<hr>
	<div class="row-fluid">
		<div class="span12">
			<div class="row-fluid">
				<div class="span9">
					Copyright ©2012-<?php echo date("Y"); ?> Cyril Rezé / iCagenda -&nbsp;
					<?php echo JText::_('COM_ICAGENDA_PANEL_COPYRIGHT');?>&nbsp;<a href="http://extensions.joomla.org/extensions/calendars-a-events/events/events-management/22013" target="_blank">Joomla! Extensions Directory</a>.
					<br />
					<br />
				</div>
				<div class="span3" style="text-align: right">
					<a href='https://www.icagenda.com' target='_blank'>
						<img src="../media/com_icagenda/images/logo_joomlic.png" alt="" border="0"/>
					</a>
					<br />
					<i><b><?php echo JText::_('COM_ICAGENDA_PANEL_SITE_VISIT');?>&nbsp;<a href='https://www.icagenda.com' target='_blank'>www.icagenda.com</a></b></i>
				</div>
			</div>
		</div>
	</div>
</div>
