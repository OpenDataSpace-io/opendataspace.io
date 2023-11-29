import React, { useEffect, useState } from 'react';
//import Head from "next/head";

const ThingList = () => {
  const [data, setData] = useState(null);

  useEffect(() => {
    fetch('/things.jsonld')
      .then(response => response.json())
      .then(json => setData(json));
  }, []);

  if (!data) {
    return <div>Loading...</div>;
  }

  return (
    <div className="container mx-auto max-w-7xl items-center justify-between p-6 lg:px-8">
        <div className="flex">
            <div className="float-right w-[1010px] justify-center">
            <div className="grid grid-cols-5 gap-4">
        {data.map(item => (
            <div key={item.id}>{item.id} - {item.name}</div>
        ))}
        </div>
         </div>
        </div>
    </div>
  );
};

export default ThingList;