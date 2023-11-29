import dynamic from "next/dynamic";

const Things = dynamic(() => import("@/components/thing/test2"), {
  ssr: false,
});

const ThingPage = () => (
  <>
    <Things />
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
export default ThingPage;
