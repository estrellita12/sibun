import React from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";
import Postcode from "@actbase/react-daum-postcode";
import styled from "styled-components";

const KakaoAddress = ({ setOpenaddr, storeInfo, setStoreInfo }) => {
  const navigate = useNavigate();
  const getAddressData = async (e) => {
    const searchTxt = e?.address;

    await axios
      .get(
        `https://dapi.kakao.com/v2/local/search/address.json?query=${searchTxt}`,
        {
          headers: {
            Authorization: `KakaoAK 1e23c3208bc2335311e9f8653b38f85b`,
          },
        }
      )
      .then((res) => {
        const location = res.data.documents[0];
        const address = location.address;
        setStoreInfo({
          ...storeInfo,
          store_addr_zip: e?.zonecode,
          store_addr1: e?.address,
          store_addr_x: address.x,
          store_addr_y: location.address.y,
          store_addr_sep1: address.region_1depth_name,
          store_addr_sep2: address.region_2depth_name,
          store_addr_sep3: address.region_3depth_name,
        });

        setOpenaddr(false);
      });
  };

  return (
    <Kakao>
      <div id="styled">
        <Postcode
          onSelected={(data) => getAddressData(data)}
          style={{ height: "100%" }}
        />
      </div>
    </Kakao>
  );
};

const Kakao = styled.div`
  max-width: 500px;
  height: 100%;
  margin: 10px auto;

  #styled {
    height: 800px !important;
    & div {
      height: 100% !important;
    }
  }
`;

export default KakaoAddress;
