import Component from 'flarum/common/Component';
import Select from 'flarum/common/components/Select';
import saveSettings from 'flarum/forum/utils/saveSettings';

export default class ThemeSwitcher extends Component {
  oninit(vnode) {
    super.oninit(vnode);
    this.loading = false;
  }

  view() {
    const themes = {
      default: app.translator.trans('demo-theme-switcher.forum.settings.options.default'),
      dark: app.translator.trans('demo-theme-switcher.forum.settings.options.dark'),
      pink: app.translator.trans('demo-theme-switcher.forum.settings.options.pink'),
    };

    return (
      <div className="Form-group">
        <label>{app.translator.trans('demo-theme-switcher.forum.settings.title')}</label>
        <Select
          value={app.session.user.preferences().theme || 'default'}
          options={themes}
          onchange={this.saveTheme.bind(this)}
          disabled={this.loading}
        />
        <p className="helpText">{app.translator.trans('demo-theme-switcher.forum.settings.help')}</p>
      </div>
    );
  }

  saveTheme(value) {
    this.loading = true;
    m.redraw();

    saveSettings({ theme: value })
      .then(() => {
        this.loading = false;
        m.redraw();
        // 立即刷新让新 CSS 生效
        window.location.reload();
      });
  }
}
