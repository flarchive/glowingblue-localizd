import app from 'flarum/admin/app';
import { extend, override } from 'flarum/common/extend';
import { slug } from '../../common';
import TranslationKey from '../components/TranslationKey';
import FieldSet from 'flarum/common/components/FieldSet';

// Make translation calls shorter
const prfx = `${slug}.forum`;

export default function modifySEOPage() {
	const components = require('@v17development-seo');

	const {
		components: { SeoSettings },
	} = require('@v17development-seo');

	extend(SeoSettings.prototype, 'viewItems', function (items) {
		items.setContent(
			'description',
			<FieldSet
				label={app.translator.trans('core.admin.basics.forum_description_heading')}
				className={this.showField !== 'all' && this.showField !== 'description' ? 'hidden' : ''}
			>
				<div className="helpText">{app.translator.trans('core.admin.basics.forum_description_text')}</div>
				<TranslationKey translation={`${prfx}.forum_description`} />
			</FieldSet>
		);

		items.setContent(
			'keywords',

			<FieldSet label="Forum keywords" className={this.showField !== 'all' && this.showField !== 'keywords' ? 'hidden' : ''}>
				<div className="helpText">Enter one or more keywords that describes your forum.</div>
				<TranslationKey translation={`${prfx}.forum_keywords`} />
				<div className="helpText">
					<b>Note: Separate keywords with a comma.</b> Example: <i>flarum, web development, forum, apples, security</i>
				</div>
			</FieldSet>
		);
	});

	const {
		pages: { HealthCheck },
	} = require('@v17development-seo');

	override(HealthCheck.prototype, 'forumDescription', function (this) {
		let reason = "It wasn't possible to determine if your forum has a description. Please check Linguist to see if you have one.";
		let passed = false;

		return (
			<tr>
				<td>
					Your forum has a description
					{this.notPassedError(passed, reason, 'Check description', this.getSettingUrl('description'))}
				</td>
				{this.passed(passed, 'description')}
			</tr>
		);
	});

	override(HealthCheck.prototype, 'forumKeywords', function (this) {
		let reason = "It wasn't possible to determine if your forum has keywords. Please check Linguist to see if you have.";
		let passed = false;

		return (
			<tr>
				<td>
					Your forum has keywords set up
					{this.notPassedError(passed, reason, 'Check keywords', this.getSettingUrl('keywords'))}
				</td>
				{this.passed(passed, 'keywords')}
			</tr>
		);
	});
}
