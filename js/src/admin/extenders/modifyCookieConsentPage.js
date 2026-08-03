/*
 * This file is part of glowingblue/localizd.
 *
 * Copyright (c) 2022 Glowing Blue AG.
 * Authors: Rafael Horvat.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

import app from 'flarum/admin/app';
import { components } from '@fof-cookie-consent';
import { extend } from 'flarum/common/extend';
import TranslationKey from '../components/TranslationKey';
import { slug } from '../../common';

// Make translation calls shorter
const t = app.translator.trans.bind(app.translator);
const cookieConsentSlug = 'fof-cookie-consent';
const prfx = `${slug}.forum.${cookieConsentSlug}`;
const cookieConsentPrfx = `${cookieConsentSlug}.admin.settings`;

export default function () {
	if (app.initializers.has(cookieConsentSlug)) {
		extend(components.CookieConsentSettingsPage.prototype, 'settingsFields', function (items) {
			items.replace(
				'consentText',
				<div className="Form-group">
					<label>{t(`${cookieConsentPrfx}.consentText`)}</label>
					<TranslationKey translation={`${prfx}.consentText`} />
				</div>,
				100
			);

			items.replace(
				'buttonText',
				<div className="Form-group">
					<label>{t(`${cookieConsentPrfx}.buttonText`)}</label>
					<TranslationKey translation={`${prfx}.buttonText`} />
				</div>,
				100
			);
		});

		extend(components.CookieConsentSettingsPage.prototype, 'learnMoreLinkItems', function (items) {
			items.replace(
				'text',
				<div className="Form-group">
					<label>{t(`${cookieConsentPrfx}.learnMoreLinkText`)}</label>
					<TranslationKey translation={`${prfx}.learnMoreLinkText`} />
				</div>
			);
			items.replace(
				'url',
				<div className="Form-group">
					<label>{t(`${cookieConsentPrfx}.learnMoreLinkUrl`)}</label>
					<TranslationKey translation={`${prfx}.learnMoreLinkUrl`} />
				</div>
			);
		});
	}
}
