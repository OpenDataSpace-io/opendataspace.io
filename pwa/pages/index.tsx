import { useEffect, useState } from 'react';
import { useRouter } from 'next/router';
import { Loading } from "@/components/common/Loading";

const HomePage = () => {
  const router = useRouter();
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    // Simulate an asynchronous operation
    setTimeout(() => {
      setIsLoading(false);
      router.push('/things');
    }, 2000);
  }, []);

  if (isLoading) {
    return <Loading />;
  }

  return null;
};

export default HomePage;