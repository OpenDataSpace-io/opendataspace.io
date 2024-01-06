import type {
    GetServerSideProps,
    InferGetServerSidePropsType,
} from 'next';

import { serverSideTranslations } from 'next-i18next/serverSideTranslations';

import { New } from "@/components/thing/New";
import { Thing } from "@/types/Thing";
import { type FetchResponse, fetch } from "@/utils/dataAccess";

export const getServerSideProps: GetServerSideProps<{
    data: Thing | null,
    hubURL: string | null,
    page: number, // required for reviews pagination, prevents useRouter
}> = async ({ query: { id, page }, locale }) => {

    return {
        props: {
            data: null,
            hubURL: null,
            page: Number(page ?? 1),
            ...(await serverSideTranslations(locale ?? 'en', [
                'common'
            ])),
        }
    };
}

export default New;