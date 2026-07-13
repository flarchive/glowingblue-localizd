/*
 * This file is part of glowingblue/localizd.
 *
 * Copyright (c) Glowing Blue AG.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

import app from 'flarum/admin/app';
import BasicsPage from 'flarum/admin/components/BasicsPage';
import { extend } from 'flarum/common/extend';
import { slug } from '../../common';
import TranslationKey from '../components/TranslationKey';

// Make translation calls shorter
const prfx = `${slug}.forum`;

export default function () {
	extend(BasicsPage.prototype, 'contentItems', function (items) {
		if (items.has('forum-title')) {
			items.setContent(
				'forum-title',
				<div className="Form-group">
					<label>{app.translator.trans('core.admin.basics.forum_title_heading')}</label>
					<TranslationKey translation={`${prfx}.forum_title`} />
				</div>
			);
		}

		if (items.has('forum-description')) {
			items.setContent(
				'forum-description',
				<div className="Form-group">
					<label>{app.translator.trans('core.admin.basics.forum_description_heading')}</label>
					<div className="helpText">{app.translator.trans('core.admin.basics.forum_description_text')}</div>
					<TranslationKey translation={`${prfx}.forum_description`} />
				</div>
			);
		}

		if (items.has('welcome-banner')) {
			items.setContent(
				'welcome-banner',
				<div className="Form-group BasicsPage-welcomeBanner-input">
					<label>{app.translator.trans('core.admin.basics.welcome_banner_heading')}</label>
					<div className="helpText">{app.translator.trans('core.admin.basics.welcome_banner_text')}</div>
					<TranslationKey translation={`${prfx}.welcome_title`} />
					<br />
					<br />
					<TranslationKey translation={`${prfx}.welcome_message`} />
				</div>
			);
		}
	});
}
