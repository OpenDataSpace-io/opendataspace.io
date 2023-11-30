import Image from "next/image";
import Link from "next/link";
import { type FunctionComponent } from "react";

import { type Thing } from "@/types/Thing";
import { getItemPath } from "@/utils/dataAccess";
import { useThing } from "@/utils/thing";
import { Loading } from "@/components/common/Loading";
import React, { useEffect, useState } from 'react';

interface Props {
  thing: Thing;
}

export const Item: FunctionComponent<Props> = ({ thing }) => {
  return (
    <div className="relative p-4 bg-white hover:drop-shadow-xl border-b border-gray-200 text-center" data-testid="thing">
      <div className="h-40 mb-2">
        <Link href={getItemPath(thing, "/things/[id]")}>
          <span className="text-slate-300 block h-full">No cover</span>
        </Link>
      </div>
      <div className="h-32 mb-2">
        <p>
          <Link href={getItemPath(thing, "/things/[id]")}
                className="font-bold text-lg text-gray-700 hover:underline">
            {thing["name"]}
          </Link>
        </p>
      </div>
    </div>
  );
};