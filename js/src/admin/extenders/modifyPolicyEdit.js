/*
 * This file is part of glowingblue/localizd.
 *
 * Copyright (c) 2024 Glowing Blue AG.
 * Authors: Davide Iadeluca, Rafael Horvat.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

import app from 'flarum/admin/app';
import { components } from '@fof-terms';
import { extend } from 'flarum/common/extend';
import TranslationKey from '../components/TranslationKey';
import { slug } from '../../common';
import result from 'lodash.result';
import { findChild } from '../utils/vdom';

// Make translation calls shorter
const t = app.translator.trans.bind(app.translator);
const prfx = `${slug}.dynamic.policy`;
const termsSlug = 'fof-terms';
const termsPrfx = `${termsSlug}.admin.policies`;

export default function () {
	if (app.initializers.has(termsSlug)) {
		extend(components.TermsSettingsPage.prototype, 'view', function (vnode) {
			const container = findChild(vnode, 'ExtensionPage-settings', true);
			container.children[0].children[0].children[1] = <TranslationKey translation={`${slug}.forum.${termsSlug}.signup-legal-text`} />;
		});

		extend(components.PolicyEdit.prototype, 'fields', function (fields) {
			// Only customize the fields if editing an EXISTING policy.
			if (!result(this, 'policy.exists', false)) {
				return;
			}

			const id = this.policy.id();

			fields.replace(
				'name',
				<div className="Form-group">
					<label>{t(`${termsPrfx}.name`)}</label>
					<TranslationKey translation={`${prfx}.${id}.name`} />
				</div>,
				100
			);

			fields.replace(
				'url',
				<div className="Form-group">
					<label>{t(`${termsPrfx}.url`)}</label>
					<TranslationKey translation={`${prfx}.${id}.url`} />
					<div className="helpText">{t(`${termsPrfx}.url-help`)}</div>
				</div>,
				95
			);

			fields.replace(
				'update-message',
				<div className="Form-group">
					<label>{t(`${termsPrfx}.update-message`)}</label>
					<TranslationKey translation={`${prfx}.${id}.update-message`} />
					<div className="helpText">{t(`${termsPrfx}.update-message-help`)}</div>
				</div>,
				90
			);
		});
	}
}
