import { extend } from 'flarum/common/extend';
import SettingsPage from 'flarum/forum/components/SettingsPage';
import ThemeSwitcher from './components/ThemeSwitcher';

app.initializers.add('demo-theme-switcher', () => {
  extend(SettingsPage.prototype, 'settingsItems', function (items) {
    items.add(
      'theme-switcher',
      <ThemeSwitcher />,
      10 // 排序，越小越靠上
    );
  });
});
