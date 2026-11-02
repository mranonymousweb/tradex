export type T = (key: string) => string;

export type Plugins = {
  deposit_request_plugin: boolean;
};

export interface Guide {
  url: string | null;
  title: string;
  text: string;
  plugins: Plugins;
}

export interface Logo {
  lightLogo: string;
  darkLogo: string;
  favicon: string;
}
