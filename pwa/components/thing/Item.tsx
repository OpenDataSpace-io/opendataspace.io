import Image from "next/image";
import Link from "next/link";
import { type FunctionComponent } from "react";

import { type Thing } from "@/types/Thing";
import { getItemPath } from "@/utils/dataAccess";
import { useOpenLibraryThing } from "@/utils/thing";
import { Loading } from "@/components/common/Loading";

interface Props {
  thing: Thing;
}

export const Item: FunctionComponent<Props> = ({ thing }) => {
  const { data, isLoading } = useOpenLibraryThing(thing);

  if (isLoading || !data) return <Loading/>;

  return (
    <div className="relative p-4 bg-white hover:drop-shadow-xl border-b border-gray-200 text-center" data-testid="thing">
      <div className="h-40 mb-2">
        <Link href={getItemPath(data, "/things/[id]")}>
          {!!data["images"] && (
            <Image alt={data["name"]} width={100} height={130} src={data["images"]["medium"]}
                   className="mx-auto w-auto max-w-[150px] h-auto max-h-[165px]" priority={true}
            />
          ) || (
            <span className="text-slate-300 block h-full">No cover</span>
          )}
        </Link>
      </div>
      <div className="h-32 mb-2">
        <p>
          <Link href={getItemPath(data, "/things/[id]")}
                className="font-bold text-lg text-gray-700 hover:underline">
            {data["name"]}
          </Link>
        </p>
      </div>
    </div>
  );
};
