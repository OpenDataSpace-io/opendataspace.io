import dynamic from "next/dynamic";

const Admin = dynamic(() => import("@/components/app/Admin"), {
  ssr: false,
});

const AppPage = () => (
  <>
    <Admin />
    <style jsx global>
      {`
      body {
        margin: 0;
        padding: 0;
        font-family: sans-serif;
      }
      `}
    </style>
  </>
);
export default AppPage;
