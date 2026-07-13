import app from 'flarum/admin/app';
import Component, { ComponentAttrs } from 'flarum/common/Component';
import Alert from 'flarum/common/components/Alert';
import Button from 'flarum/common/components/Button';
import LoadingIndicator from 'flarum/common/components/LoadingIndicator';
import Stream from 'flarum/common/utils/Stream';

import { components } from '@fof-linguist';

import type Mithril from 'mithril';
import StringKey from '@fof-linguist/models/StringKey';

export interface ILinguistTranslateAttrs extends ComponentAttrs {
	key: string;
}

export default class LinguistTranslate<CustomAttrs extends ILinguistTranslateAttrs = ILinguistTranslateAttrs> extends Component<CustomAttrs> {
	private stringKey?: StringKey;

	private clear!: Stream<boolean>;

	private clearing!: Stream<boolean>;

	oninit(vnode: Mithril.Vnode): void {
		super.oninit(vnode);

		const { key } = this.attrs;

		app.store.find('fof/linguist/strings', key).then(() => {
			app.store.find<StringKey>('fof/linguist/string-keys', key).then((stringKey) => {
				this.stringKey = stringKey;
				m.redraw();
			});
		});

		this.clear = new Stream(false);
		this.clearing = new Stream(false);
	}

	className() {
		return 'TranslateModal Modal--large';
	}

	title() {
		return app.translator.trans('glowingblue-localizd.admin.translation_key.modal_title');
	}

	view() {
		const { stringKey } = this;

		if (!stringKey) return <LoadingIndicator />;

		return (
			<div>
				{this.clear() && (
					<Alert
						dismissible={false}
						controls={[
							<Button
								className="Button Button--link"
								loading={this.clearing()}
								onclick={() => {
									this.clearing(true);

									app
										.request({
											method: 'DELETE',
											url: app.forum.attribute('apiUrl') + '/cache',
										})
										.then(() => {
											this.clearing(false);
											this.clear(false);
											m.redraw();
										});
								}}
							>
								{app.translator.trans('fof-linguist.admin.clear-cache.button')}
							</Button>,
						]}
					>
						{app.translator.trans('fof-linguist.admin.clear-cache.text')}
					</Alert>
				)}
				<components.StringKey stringKey={stringKey} onchange={() => this.clear(true)} />
			</div>
		);
	}
}
