import Image from "next/image";
import Link from "next/link";
import { type FunctionComponent } from "react";

import { type Thing } from "@/types/Thing";
import { getItemPath } from "@/utils/dataAccess";
import { Loading } from "@/components/common/Loading";
import React, { useEffect, useState } from 'react';
import { useTranslation } from 'next-i18next';

interface Props {
  thing: Thing;
}

export const Item: FunctionComponent<Props> = ({ thing }) => {
  const [isLoading, setIsLoading] = useState(true);
  const { t } = useTranslation('common');

  useEffect(() => {
    if (thing) {
      setIsLoading(false);
    }
  }, [thing]);

  if (isLoading || !thing) return <Loading />;

  return (
    <div className="relative p-4 bg-white hover:drop-shadow-xl border-b border-gray-200 text-center" data-testid="thing">
      <div className="h-40 mb-2">
        <Link href={getItemPath(thing['@id'], "/things/[id]")}>
        {!!thing["image"] && (
            <Image alt={thing["name"]} width={100} height={130} src={thing["image"]}
                   className="mx-auto w-auto max-w-[150px] h-auto max-h-[165px]" priority={true}
            />
          ) || (
            <span className="text-slate-300 block h-full">{t('things.show.noimage')}</span>
          )}
        </Link>
      </div>
      <div className="h-32 mb-2">
        <p>
          <Link href={getItemPath(thing['@id'], "/things/[id]")}
                className="font-bold text-lg text-gray-700 hover:underline">
            {thing["name"]}
          </Link>
        </p>
      </div>
    </div>
  );
};