/*
 * This file is part of glowingblue/localizd.
 *
 * Copyright (c) 2022 Glowing Blue AG.
 * Authors: Ian Morland, Rafael Horvat.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

import app from 'flarum/admin/app';
import { slug } from '../../common';
import TranslationKey from '../components/TranslationKey';

// Make translation calls shorter
const t = app.translator.trans.bind(app.translator);
const prfx = `${slug}.forum.flarum-flags`;
const flagsSlug = 'flarum-flags';

export default function () {
	if (app.initializers.has(flagsSlug)) {
		app.extensionData
			.for(flagsSlug)
			.registerSetting(
				{
					setting: `${flagsSlug}.guidelines_url`,
					type: 'hidden',
				},
				15
			)
			.registerSetting(function () {
				return (
					<div class="Form-group">
						<label>{t(`${flagsSlug}.admin.settings.guidelines_url_label`)}</label>
						<TranslationKey translation={`${prfx}.guidelines_url`} />
					</div>
				);
			}, 16);
	}
}
