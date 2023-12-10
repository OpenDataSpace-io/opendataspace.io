/** @type {import("next").NextConfig} */
const nextConfig = {
  reactStrictMode: true,
  output: "standalone",
  images: {
    remotePatterns: [
      {
        protocol: "https",
        hostname: "covers.openlibrary.org",
        port: "",
        pathname: "/b/id/**"
      },
      {
        protocol: "https",
        hostname: "dummyimage.com",
        port: "",
        pathname: ""
      },
    ]
  }
}

module.exports = nextConfig;
