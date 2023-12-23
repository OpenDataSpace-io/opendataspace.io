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
        hostname: "covers.openlibrary.org",
        port: "",
        pathname: "/b/olid/**"
      }
    ]
  }
}

module.exports = nextConfig;
