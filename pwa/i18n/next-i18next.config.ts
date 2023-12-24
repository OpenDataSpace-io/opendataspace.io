export const i18n = {
  locales: ["en", "de"],
  defaultLocale: "en",
  setLocales: "en"
};

export type I18nConfig = typeof i18n;
export type Locale = I18nConfig["locales"][number];