import dynamic from "next/dynamic";

const Admin = dynamic(() => import("@/components/dashboard/Admin"), {
  ssr: false,
});

const DashboardPage = () => (
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
export default DashboardPage;
