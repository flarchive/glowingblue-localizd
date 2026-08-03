import app from 'flarum/admin/app';
import Component, { ComponentAttrs } from 'flarum/common/Component';
import Link from 'flarum/common/components/Link';
import LinguistTranslate from './LinguistTranslate';
import CustomDropdown from './CustomDropdown';

export interface ITranslationKeyAttrs extends ComponentAttrs {
	translation: string;
}

export default class TranslationKey<CustomAttrs extends ITranslationKeyAttrs = ITranslationKeyAttrs> extends Component<CustomAttrs> {
	view(): JSX.Element {
		const { translation } = this.attrs;

		return (
			<div className="TranslationKey">
				<p className="helpText">
					<span class="TranslationKey-label">
						{app.translator.trans('glowingblue-localizd.admin.translation_key.help', {
							a: <Link href={app.route('extension', { id: 'fof-linguist' })} />,
						})}
					</span>

					<CustomDropdown
						menuClassName="LinguistTranslateDropdown"
						buttonClassName="Button Button--primary"
						label={app.translator.trans('glowingblue-localizd.admin.translation_key.translate')}
					>
						<LinguistTranslate key={translation} />
					</CustomDropdown>
				</p>
			</div>
		);
	}
}
