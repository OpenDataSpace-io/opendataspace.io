// @ts-check
const { i18n } = require('./next-i18next.config.js')

/** @type {import("next").NextConfig} */
const nextConfig = {
  reactStrictMode: true,
  output: "standalone",
  images: {
    remotePatterns: [
      {
        protocol: "https",
        hostname: "sos-ch-dk-2.exo.io",
        port: "",
        pathname: "/opendataspace/**"
      },
      {
        protocol: "https",
        hostname: "opendataspace.sos-ch-dk-2.exoscale-cdn.com",
        port: "",
        pathname: "/**"
      }
    ]
  },
  i18n: {
    ...i18n,
    localeDetection: false
  },
}

module.exports = nextConfig;
