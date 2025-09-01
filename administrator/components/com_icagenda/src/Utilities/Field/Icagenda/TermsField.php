<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-19
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.Field.Icagenda
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.7.0
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Field\Icagenda;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

use Joomla\CMS\Layout\LayoutHelper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Terms Form Field class for iCagenda.
 */
class TermsField extends ListField
{
	/**
	 * The form field type.
	 *
	 * @var     string
	 */
	protected $type = 'Terms';

	/**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 */
	protected function getLabel()
	{
		$app    = Factory::getApplication();
		$params = $app->getParams();
		$input  = $app->input;

		// Get the site name
		$sitename = $app->getCfg('sitename');

		// Terms options
		$slug = $this->element['slug'];

		$terms_type   = $params->get($slug . '_type', '');
		$termsArticle = $params->get($slug . 'Article', '');
		$termsContent = $params->get($slug . 'Content', '');

		$view_str = strtoupper($input->get('view'));
		$slug_str = strtoupper($slug);

		$DEFAULT_STRING = Text::_('COM_ICAGENDA_' . $view_str . '_' . $slug_str);

		$default = str_replace('[SITENAME]', $sitename, $DEFAULT_STRING);
		$article = 'index.php?option=com_content&view=article&id=' . $termsArticle . '&tmpl=component';
		$custom  = $termsContent;

		$html = '';

		$legend = '<legend style="display: none;" class="ic-' . $slug . '-legend" id="' . $this->id . '-lbl" for="' . $this->name . '">';
		$legend.= '	' . Text::_('COM_ICAGENDA_' . $view_str . '_CONSENT_' . $slug_str . '_LABEL');
		$legend.= '</legend>';

		$html.= $legend;

		$html.= '<div class="ic-' . $slug . '-text">';

		switch ($terms_type) {
			case 1:
				$html .= '<iframe src="' . htmlentities($article) . '" width="100%" height="200"></iframe>';
				break;

			case 2:
				$html .= $custom;
				break;

			case 3:
				$html .= $default;
				break;

			default:
				return '</div>' . $legend . '<div>';
		}

		return '</div>' . $html;
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		$app    = Factory::getApplication();
		$params = $app->getParams();
		$input  = $app->input;

		$slug = $this->element['slug'];

		// True if the field has 'value' set. In other words, it has been stored, don't use the default values.
		$hasValue = (isset($this->value) && ! empty($this->value));

		// Initialize some field attributes.
		$class          = ! empty($this->class) ? ' class="checkboxes ic-' . $slug . '-consent ' . trim($this->class) . '"' : ' class="checkboxes ic-' . $slug . '-consent"';
		$checkedOptions = explode(',', (string) $this->checkedOptions);
		$required       = $this->required ? ' required aria-required="true"' : '';
		$autofocus      = $this->autofocus ? ' autofocus' : '';

		// Including fallback code for HTML5 non supported browsers.
		HTMLHelper::_('jquery.framework');
		HTMLHelper::_('script', 'system/html5fallback.js', array('version' => 'auto', 'relative' => true, 'conditional' => 'lt IE 9'));

		// Get the field options.
		$options = $this->getOptions();

		/**
		 * The format of the input tag to be filled in using sprintf.
		 *     %1 - id
		 *     %2 - name
		 *     %3 - value
		 *     %4 = any other attributes
		 */
		$format = '<input class="form-check-input" type="checkbox" id="%1$s" name="%2$s" value="%3$s" %4$s />';

		// Start the checkbox field output.
		$html = '<fieldset id="' . $this->id . '"' . $class . $required . $autofocus . '>';

		$terms_type   = $params->get($slug . '_type', '');
		$termsArticle = $params->get($slug . 'Article', '');
		$termsContent = $params->get($slug . 'Content', '');

		$view_str = strtoupper($input->get('view'));
		$slug_str = strtoupper($slug);

		if ($params->get('terms_mode', '') == 1) {
			//
			// IN DEV.
			//
			if (\in_array($terms_type, [1, 2, 3])) {
				// Terms Modal link
				$terms_link = '<a data-toggle="modal"'
					. ' role="button"'
					. ' href="#ModalTerms' . $this->id . '">'
					. $this->title
					. '</a>';
			} else {
				$terms_link = Text::sprintf('COM_ICAGENDA_' . $view_str . '_CONSENT_' . $slug_str . '_OF_THIS_WEBSITE', $this->title);
			}

			if ($terms_type == 1) {
				// Terms Modal: Article
				$html .= HTMLHelper::_(
					'bootstrap.renderModal',
					'ModalTerms' . $this->id,
					[
						'title'       => $this->title,
						'url'         => 'index.php?option=com_content&view=article&id=' . $termsArticle . '&tmpl=component',
						'height'      => '400px',
						'width'       => '800px',
						'bodyHeight'  => '70',
						'modalWidth'  => '80',
						'footer'      => '<a role="button" class="btn" data-dismiss="modal" aria-hidden="true">' . Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</a>',
					]
				);
			} elseif ($terms_type == 2) {
				// Terms Modal: Custom Content
				$termsContentModal = [
					'selector' => 'ModalTerms' . $this->id,
					'params'   => [
						'title'  => $this->title,
						'footer' => '<a role="button" class="btn" data-dismiss="modal" aria-hidden="true">' . Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</a>',
					],
					'body'     => $termsContent,
				];

				$html .= LayoutHelper::render('joomla.modal.main', $termsContentModal);
			} elseif ($terms_type == 3) {
				// Terms Modal: Custom Content
				$termsContentModal = [
					'selector' => 'ModalTerms' . $this->id,
					'params'   => [
						'title'  => $this->title,
						'footer' => '<a role="button" class="btn" data-dismiss="modal" aria-hidden="true">' . Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</a>',
					],
					'body'     => Text::_('COM_ICAGENDA_' . $view_str . '_' . $slug_str),
				];

				$html .= LayoutHelper::render('joomla.modal.main', $termsContentModal);
			}
		} else {
			$terms_link = Text::sprintf('COM_ICAGENDA_' . $view_str . '_CONSENT_' . $slug_str . '_OF_THIS_WEBSITE', $this->title);
		}


		foreach ($options as $i => $option) {
			// Initialize some option attributes.
			$checked = \in_array((string) $option->value, $checkedOptions, true) ? 'checked' : '';

			// In case there is no stored value, use the option's default state.
			$checked        = ( ! $hasValue && $option->checked) ? 'checked' : $checked;
			$optionClass    = ! empty($option->class) ? 'class="' . $option->class . '"' : '';
			$optionDisabled = ! empty($option->disable) || $this->disabled ? 'disabled' : '';

			// Initialize some JavaScript option attributes.
			$onclick  = ! empty($option->onclick) ? 'onclick="' . $option->onclick . '"' : '';
			$onchange = ! empty($option->onchange) ? 'onchange="' . $option->onchange . '"' : '';

			$oid        = $this->id . $i;
			$value      = htmlspecialchars($option->value, ENT_COMPAT, 'UTF-8');
			$attributes = array_filter(array($checked, $optionClass, $optionDisabled, $onchange, $onclick));

			$html .= '<label class="checkbox ic-' . $slug . '-consent-text">';
			$html .= sprintf($format, $oid, $this->name, $value, implode(' ', $attributes));

			$html .= ' ' . Text::sprintf($option->text, $terms_link) . '</label>';
		}

		// End the checkbox field output.
		$html .= '</fieldset>';

		return $html;
	}
}
