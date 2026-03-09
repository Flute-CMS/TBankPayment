@php
    $settings = $gateway ? $gateway->getSettings() : [];
    if (empty($settings)) {
        $settings = [
            'terminalKey' => '',
            'password' => '',
            'testMode' => false,
        ];
    }
@endphp

<x-alert type="info" withClose="false" class="mb-0">
    Для настройки платёжной системы T-Bank вам потребуется <strong>TerminalKey</strong> и <strong>Password</strong>
    из личного кабинета <a href="https://www.tbank.ru/kassa/" target="_blank">T-Bank Кассы</a>.
</x-alert>

<x-forms.field>
    <x-forms.label for="settings__terminalKey" required>Terminal Key:</x-forms.label>
    <x-fields.input name="settings__terminalKey" id="settings__terminalKey"
        value="{{ request()->input('settings__terminalKey', $settings['terminalKey']) }}" required
        placeholder="Введите Terminal Key из личного кабинета" />
</x-forms.field>

<x-forms.field>
    <x-forms.label for="settings__password" required>Password:</x-forms.label>
    <x-fields.input name="settings__password" id="settings__password" type="password"
        value="{{ request()->input('settings__password', $settings['password']) }}" required
        placeholder="Введите пароль терминала" />
</x-forms.field>

<x-forms.field>
    <x-forms.label for="settings__testMode" required>Тестовый режим:</x-forms.label>
    <x-fields.toggle name="settings__testMode" id="settings__testMode"
        checked="{{ request()->input('settings__testMode', $settings['testMode']) }}" />
</x-forms.field>
