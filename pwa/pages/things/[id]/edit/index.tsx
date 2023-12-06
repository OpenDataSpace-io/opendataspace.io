import { GetServerSideProps } from "next";

import { Edit } from "@/components/thing/Edit";
import { Thing } from "@/types/Thing";
import { type FetchResponse, fetch } from "@/utils/dataAccess";

export const getServerSideProps: GetServerSideProps<{
  data: Thing,
  hubURL: string | null,
  page: number, // required for reviews pagination, prevents useRouter
}> = async ({ query: { id, page } }) => {
  try {
    const response: FetchResponse<Thing> | undefined = await fetch(`/things/${id}`, {
      headers: {
      }
    });
    if (!response?.data) {
      throw new Error(`Unable to retrieve data from /things/${id}.`);
    }

    return { props: { data: response.data, hubURL: response.hubURL, page: Number(page ?? 1) } };
  } catch (error) {
    console.error(error);
  }

  return { notFound: true };
};

export default Edit;
