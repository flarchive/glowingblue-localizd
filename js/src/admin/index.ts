/*
 * This file is part of glowingblue/localizd.
 *
 * Copyright (c) Glowing Blue AG.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

import app from 'flarum/admin/app';

import modifyAppearancePage from './extenders/modifyAppearancePage';
import modifyBasicsPage from './extenders/modifyBasicsPage';
import modifyCookieConsentPage from './extenders/modifyCookieConsentPage';
import modifyEditTagModal from './extenders/modifyEditTagModal';
import modifyEditTaxonomiesModal from './extenders/modifyEditTaxonomiesModal';
import modifyFlagsModal from './extenders/modifyFlagsModal';
import modifyLinksModal from './extenders/modifyLinksModal';
import modifyPolicyEdit from './extenders/modifyPolicyEdit';
import modifyReactionsSettings from './extenders/modifyReactionsSettings';
import modifyEditGroupModal from './extenders/modifyEditGroupModal';
import modifyProfileFieldsEdit from './extenders/modifyProfileFieldsEdit';
import modifySEOPage from './extenders/modifySEOPage';
import { slug } from '../common';

// Make translation calls shorter
const t = app.translator.trans.bind(app.translator);
const prfx = `${slug}.admin.settings`;

app.initializers.add(slug, () => {
	app.extensionData.for(slug).registerSetting({
		label: t(`${prfx}.redirect_en`),
		help: t(`${prfx}.redirect_en_help`),
		setting: `${slug}.redirect-en`,
		type: 'bool',
	});

	modifyAppearancePage();
	modifyBasicsPage();
	modifyCookieConsentPage();
	modifyEditTagModal();
	modifyEditTaxonomiesModal();
	modifyFlagsModal();
	modifyLinksModal();
	modifyPolicyEdit();
	modifyReactionsSettings();
	modifyEditGroupModal();

	if (app.initializers.has('fof-masquerade')) {
		modifyProfileFieldsEdit();
	}

	if (app.initializers.has('v17development-flarum-seo')) {
		modifySEOPage();
	}
});

// Expose compat API
import localizdCompat from './compat';
import { compat } from '@flarum/core/forum';

Object.assign(compat, localizdCompat);
