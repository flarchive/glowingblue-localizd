import SelectDropdown from 'flarum/common/components/SelectDropdown';
import type Mithril from 'mithril';
export default class CustomDropdown extends SelectDropdown {
    oncreate(vnode: Mithril.Vnode): void;
    getButtonContent(): JSX.Element[];
}
