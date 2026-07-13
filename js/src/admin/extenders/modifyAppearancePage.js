/*
 * This file is part of glowingblue/localizd.
 *
 * Copyright (c) Glowing Blue AG.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

import app from 'flarum/admin/app';
import AppearancePage from 'flarum/admin/components/AppearancePage';
import { extend } from 'flarum/common/extend';
import { slug } from '../../common';
import TranslationKey from '../components/TranslationKey';

// Make translation calls shorter
const prfx = `${slug}.forum`;

export default function () {
	extend(AppearancePage.prototype, 'contentItems', function (items) {
		if (items.has('custom-header')) {
			items.setContent(
				'custom-header',
				<fieldset>
					<legend>{app.translator.trans('core.admin.appearance.custom_header_heading')}</legend>
					<div className="helpText">{app.translator.trans('core.admin.appearance.custom_header_text')}</div>
					<TranslationKey translation={`${prfx}.custom_header`} />
				</fieldset>
			);
		}

		if (items.has('custom-footer')) {
			items.setContent(
				'custom-footer',
				<fieldset>
					<legend>{app.translator.trans('core.admin.appearance.custom_footer_heading')}</legend>
					<div className="helpText">{app.translator.trans('core.admin.appearance.custom_footer_text')}</div>
					<TranslationKey translation={`${prfx}.custom_footer`} />
				</fieldset>
			);
		}
	});
}
